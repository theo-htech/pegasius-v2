<?php

namespace App\Controller\Secure;

use App\Entity\Poll;
use App\Entity\Survey;
use App\Factory\PollFactory;
use App\Factory\SurveyFactory;
use App\Factory\SurveyFillUpRequestFactory;
use App\Factory\SurveyTargetFactory;
use App\Form\SurveyType;
use App\Repository\AnswerRepository;
use App\Repository\PollRepository;
use App\Repository\SurveyFillUpRequestRepository;
use App\Repository\SurveyRepository;
use App\Service\AnswerArrayService;
use App\Service\MTService;
use App\Service\PDFService;
use App\Service\YamlSurveyParse;
use App\Twig\Runtime\GiveAnswerExtensionRuntime;
use App\Validator\SurveyTargetValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

#[Route('/survey')]
class SurveyController extends AbstractController
{
    public function __construct(private SurveyRepository    $surveyRepository,
                                private PollRepository      $pollRepository,
                                private TranslatorInterface $translator,
                                private MTService           $MTService)
    {
    }

    #[Route('/new', name: 'app_survey_new', methods: ['POST'])]
    public function new(Request $request): Response
    {

        if ($request->isMethod('POST')) {
            $formData = $request->request->all();
            if (!isset($formData['number'])) {
                throw new \InvalidArgumentException('Title is required');
            }
            $survey = SurveyFactory::createSurveyFromDashboardModal(
                $formData['title'],
                $formData['number'],
                $this->getUser()
            );
            $this->surveyRepository->save($survey);
            $this->addFlash('success', $this->translator->trans('dashboard.survey_modal.confirm'));
            $this->MTService->notifNewSurvey($survey);
        }
        return $this->redirectToRoute('app_dashboard');
    }

    #[Route('/{id}', name: 'app_survey_show', methods: ['GET'])]
    public function show(Survey $survey, SurveyFillUpRequestRepository $fillUpRequestRepository): Response
    {
        if ($survey->getCreator()->getId() != $this->getUser()->getId() && !$this->getUser()->isAdmin()) {
            throw new AccessDeniedException('Access denied');
        }

        if (!$survey->getCreator()->isAdminCanSee()) {
            throw new AccessDeniedException('Access denied');
        }
        if ($survey->getStatus() == Survey::STATUS_MANAGER_ASKED &&
            $survey->getCreator()->getEmail() == $this->getUser()->getEmail()) {
            $tokenRequest = $fillUpRequestRepository->getTokenByEmailAndSurveyId(
                $survey->getCreator()->getEmail(),
                $survey->getId()
            );
        } else {
            $tokenRequest = null;
        }
        return $this->render('survey/show.html.twig', [
            'survey' => $survey,
            'pollToken' => is_null($tokenRequest) ? null : $tokenRequest['hashedToken']
        ]);
    }


    #[Route('/{id}/cancel', name: 'app_survey_cancel', methods: ['GET'])]
    public function cancel(Survey $survey): Response
    {
        if ($survey->getCreator()->getId() != $this->getUser()->getId() && !$this->getUser()->isAdmin()) {
            throw new AccessDeniedException('Access denied');
        }

        if (!$survey->getCreator()->isAdminCanSee()) {
            throw new AccessDeniedException('Access denied');
        }
        $survey->setStatus(Survey::STATUS_CANCEL);
        $this->surveyRepository->save($survey);
        $this->addFlash('success', $this->translator->trans('dashboard.survey_show.flash'));
        $this->MTService->notifCancelSurvey($survey);

        return $this->redirectToRoute('app_dashboard');
    }


    #[Route('/{id}/valid_target', name: 'app_survey_valid_target', methods: ['POST'])]
    public function validTarget(Survey                        $survey,
                                Request                       $request,
                                SurveyTargetValidator         $surveyTargetValidator,
                                SurveyFillUpRequestRepository $fillUpRequestRepository): Response
    {
        if ($survey->getCreator()->getId() != $this->getUser()->getId() && !$this->getUser()->isAdmin()) {
            throw new AccessDeniedException('Access denied');
        }
        if (!$survey->getCreator()->isAdminCanSee()) {
            throw new AccessDeniedException('Access denied');
        }
        $data_array = $request->get('data');
        $surveyTargets = SurveyTargetFactory::createFromArray($data_array);
        if (empty($surveyTargets)) {
            throw new \InvalidArgumentException('Data is required');
        }

//        if (!$surveyTargetValidator->hasAllType($surveyTargets)) {
//            throw new \InvalidArgumentException('All type required is not use');
//        }

        if (!$surveyTargetValidator->hasSingleEmail($surveyTargets)) {
            throw new \InvalidArgumentException('You cannot send a survey twice with same email');
        }

        $salaryPoll = PollFactory::createSalaryPoll();
        $salaryPoll->setSurvey($survey);
        $this->pollRepository->save($salaryPoll);
        $survey->addPoll($salaryPoll);

        $this->surveyRepository->save($survey);
        foreach ($surveyTargets as $surveyTarget) {
            $poll = $survey->getPollByType($surveyTarget->getTargetType());
            $surveyFillUpRequest = SurveyFillUpRequestFactory::createSurveyRequest($poll, $surveyTarget);
            $fillUpRequestRepository->save($surveyFillUpRequest);
            $poll->addSurveyFillUpRequest($surveyFillUpRequest);
        }
        $survey->setStatus(Survey::STATUS_IDLE);
        $this->surveyRepository->save($survey);
        $this->addFlash('success', $this->translator->trans('survey_show.confirm'));
        return $this->redirectToRoute('app_survey_show', ['id' => $survey->getId()]);

    }

    #[Route('/{id}/result', name: 'app_survey_see_result', methods: ['GET'])]
    public function seeSurveyResult(Survey                $survey,
                                    AnswerArrayService    $arrayService)
    {
        if ($survey->getCreator()->getId() != $this->getUser()->getId() && !$this->getUser()->isAdmin()) {
            throw new AccessDeniedException('Access denied');
        }
        if (!$survey->getCreator()->isAdminCanSee()) {
            throw new AccessDeniedException('Access denied');
        }

        if (!in_array($survey->getStatus(), Survey::VIEW_RESULT)) {
            throw new AccessDeniedException('Survey results are not available');
        }

        if ($survey->getPourcentFillUp() < 1) {
            throw new AccessDeniedException('Survey is not filled up enough');
        }
        $managerPoll = $survey->getPollByType(Poll::MANAGER);
        $salaryPoll = $survey->getPollByType(Poll::SALARY);
        $arrayManagerPoll = $arrayService->AnswersToArray($managerPoll);
        $arraySalaryPoll = $arrayService->AnswersToArray($salaryPoll);

        return $this->render('survey/result/index.html.twig', [
            'survey' => $survey,
            'managerPoll' => $arrayManagerPoll,
            'salaryPoll' => $arraySalaryPoll,
        ]);
    }


    #[Route('/{id}/result/pdf', name: 'app_survey_see_result_pdf')]
    public function seeSurveyResultPdf(Survey                $survey,
                                    AnswerArrayService    $arrayService,
                                       PDFService $pdfService)
    {
        if (
            $survey->getCreator()->getId() != $this->getUser()->getId()
            && !$this->getUser()->isAdmin()) {
            throw new AccessDeniedException('Access denied');
        }
        if (!$survey->getCreator()->isAdminCanSee()) {
            throw new AccessDeniedException('Access denied');
        }

        if (!in_array($survey->getStatus(), Survey::VIEW_RESULT)) {
            throw new AccessDeniedException('Survey results are not available');
        }

        if ($survey->getPourcentFillUp() < 1) {
            throw new AccessDeniedException('Survey is not filled up enough');
        }
        $managerPoll = $survey->getPollByType(Poll::MANAGER);
        $salaryPoll = $survey->getPollByType(Poll::SALARY);
        $arrayManagerPoll = $arrayService->AnswersToArray($managerPoll);
        $arraySalaryPoll = $arrayService->AnswersToArray($salaryPoll);

        $content = $this->renderView('pdf/result_client.html.twig', [
            'survey' => $survey,
            'managerPoll' => $arrayManagerPoll,
            'salaryPoll' => $arraySalaryPoll,
        ]);

        $pdfContent = $pdfService->generatePDF($content);
        $timestamp = date("YmdHis");

        return new Response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
         'Content-Disposition' => 'attachment; filename="O-DO-P_'
             .str_replace(' ', '-',$survey->getTitle()).'_'.$timestamp.'.pdf"'

        ]);
    }



    #[Route('/request/{token}', name: 'app_secure_survey_answer', methods: ['GET'])]
    public function requestAnswer(Request                       $request,
                                  SurveyFillUpRequestRepository $surveyFillUpRequestRepository,
                                  GiveAnswerExtensionRuntime    $answerExtensionRuntime,
                                  AnswerRepository              $answerRepository): Response
    {
        $token = $request->get('token');
        $fillUpRequest = $surveyFillUpRequestRepository->findOneBy(['hashedToken' => $token]);
        if (!$fillUpRequest) {
            throw $this->createNotFoundException('Survey fill-up request not found');
        }

        $poll = $fillUpRequest->getPoll();
        if (!$poll) {
            throw $this->createNotFoundException('Poll not found');
        }

        $survey = $fillUpRequest->getSurvey();
        if (!$survey) {
            throw $this->createNotFoundException('Survey not found');
        }

        $answers = $answerRepository->findBy(['poll' => $poll->getId()],
            [
                'bloc' => 'ASC',
                'question' => 'ASC'
            ]);
        return $this->render('survey/give_answer.html.twig', [
            'answers' => $answers,
            'config' => $answerExtensionRuntime->getConfigSurveyArray(),
            'token' => $token
        ]);
    }

    #[Route('/response/{token}', name: 'app_survey_secure_answer_response', methods: ['POST'])]
    public function responseAnswer(Request                       $request,
                                   SurveyFillUpRequestRepository $surveyFillUpRequestRepository,
                                   AnswerRepository              $answerRepository,
                                   PollRepository                $pollRepository,
                                   SurveyRepository              $surveyRepository)
    {
        $token = $request->get('token');
        $fillUpRequest = $surveyFillUpRequestRepository->findOneBy(['hashedToken' => $token]);
        if (!$fillUpRequest) {
            throw $this->createNotFoundException('Survey fill-up request not found');
        }

        $poll = $fillUpRequest->getPoll();
        if (!$poll) {
            throw $this->createNotFoundException('Poll not found');
        }

        $survey = $fillUpRequest->getSurvey();
        if (!$survey) {
            throw $this->createNotFoundException('Survey not found');
        }

        $responseArray = $request->get('response');
        $pollRepository->save($poll);
        foreach ($responseArray as $blocId => $questions) {
            foreach ($questions as $questionId => $answerValue) {
                $answer = $poll->getAnswerByBlocAndQuestion($blocId, $questionId);
                $answer->updateResponse($answerValue);
                $answerRepository->save($answer);
            }
        }

        $poll->setCountFillUp($poll->getCountFillUp() + 1);
        $pollRepository->save($poll);
        if ($survey->getStatus() == Survey::STATUS_MANAGER_ASKED) {
            $survey->setStatus(Survey::STATUS_CONFIRM);
            $surveyRepository->save($survey);

        }
        $surveyFillUpRequestRepository->remove($fillUpRequest);

        return $this->redirectToRoute('app_survey_show', ['id' => $survey->getId()]);
    }

    /*  #[Route('/{id}/edit', name: 'app_survey_edit', methods: ['GET', 'POST'])]
      public function edit(Request $request, Survey $survey, EntityManagerInterface $entityManager): Response
      {
          $form = $this->createForm(SurveyType::class, $survey);
          $form->handleRequest($request);

          if ($form->isSubmitted() && $form->isValid()) {
              $entityManager->flush();

              return $this->redirectToRoute('app_survey_index', [], Response::HTTP_SEE_OTHER);
          }

          return $this->render('survey/edit.html.twig', [
              'survey' => $survey,
              'form' => $form,
          ]);
      }*/

    /*#[Route('/{id}', name: 'app_survey_delete', methods: ['POST'])]
     public function delete(Request $request, Survey $survey, EntityManagerInterface $entityManager): Response
     {
         if ($this->isCsrfTokenValid('delete'.$survey->getId(), $request->request->get('_token'))) {
             $entityManager->remove($survey);
             $entityManager->flush();
         }

         return $this->redirectToRoute('app_survey_index', [], Response::HTTP_SEE_OTHER);
     }*/
}
