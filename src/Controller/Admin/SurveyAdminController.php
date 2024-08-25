<?php

namespace App\Controller\Admin;

use App\Entity\Poll;
use App\Entity\Survey;
use App\Entity\SurveyTarget;
use App\Factory\PollFactory;
use App\Factory\SurveyFillUpRequestFactory;
use App\Repository\PollRepository;
use App\Repository\SurveyFillUpRequestRepository;
use App\Repository\SurveyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class SurveyAdminController extends AbstractController
{
    public function __construct(private SurveyRepository    $surveyRepository,
                                private TranslatorInterface $translator,
                                private PollRepository $pollRepository)
    {
    }

    /**
     * @return Response
     */
    #[Route('/survey/confirm/{id}', name: 'app_admin_survey_confirm')]
    public function acceptSurvey(Survey $survey, SurveyFillUpRequestRepository $fillUpRequestRepository): Response
    {
        $managerPoll = PollFactory::createManagerPoll();
        $managerPoll->setSurvey($survey);
        $this->pollRepository->save($managerPoll);
        $survey->addPoll($managerPoll);
        $surveyTarget = new SurveyTarget($survey->getCreator()->getEmail(), Poll::MANAGER);
        $surveyFillUpRequest = SurveyFillUpRequestFactory::createSurveyRequest($managerPoll, $surveyTarget);
        $fillUpRequestRepository->save($surveyFillUpRequest);
        $managerPoll->addSurveyFillUpRequest($surveyFillUpRequest);
        return $this->validateChoice($survey, Survey::STATUS_MANAGER_ASKED);
    }

    /**
     * @return Response
     */
    #[Route('/survey/decline/{id}', name: 'app_admin_survey_decline')]
    public function declineSurvey(Survey $survey): Response
    {
        return $this->validateChoice($survey, Survey::STATUS_CANCEL);
    }

    /**
     * Validates and updates the status of a survey based on the given choice.
     *
     * @param Survey $survey The survey class whose be edited.
     * @param string $choice The choice to set as the status.
     * @return RedirectResponse The redirect response to the dashboard.
     *
     * @throws NotFoundHttpException
     * If the survey with the given ID is not found.
     */
    private function validateChoice($survey, string $choice): Response
    {
        if ($survey) {
            $survey->setStatus($choice);
            $this->surveyRepository->save($survey);
            $this->addFlash('success', $this->translator->trans('dashboard.survey_show.flash'));
        } else {
            throw $this->createNotFoundException('Survey not found');
        }
        return $this->redirectToRoute('app_dashboard');
    }
}
