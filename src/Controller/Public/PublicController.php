<?php

namespace App\Controller\Public;

use App\Entity\Answer;
use App\Entity\Survey;
use App\Repository\AnswerRepository;
use App\Repository\PollRepository;
use App\Repository\SurveyFillUpRequestRepository;
use App\Repository\SurveyRepository;
use App\Service\PDFService;
use App\Twig\Runtime\GiveAnswerExtensionRuntime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PublicController extends AbstractController
{
    #[Route('/survey/request/{token}', name: 'app_survey_answer', methods: ['GET'])]
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

        $creator = $fillUpRequest->getSurvey()->getCreator();
        if ($creator->getEmail() == $fillUpRequest->getEmail())
        {
            return $this->redirectToRoute('app_secure_survey_answer', ['token' => $token]);
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
        return $this->render('public/give_answer.html.twig', [
            'answers' => $answers,
            'config' => $answerExtensionRuntime->getConfigSurveyArray(),
            'token' => $token
        ]);
    }

    #[Route('/survey/response/{token}', name: 'app_survey_answer_response', methods: ['POST'])]
    public function responseAnswer(Request                       $request,
                                   SurveyFillUpRequestRepository $surveyFillUpRequestRepository,
                                   AnswerRepository              $answerRepository,
                                   PollRepository                $pollRepository,
                                   SurveyRepository            $surveyRepository)
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
        if ($survey->getStatus() == Survey::STATUS_IDLE) {
            $survey->setStatus(Survey::STATUS_ONGOING);
            $surveyRepository->save($survey);

        }

        return $this->redirectToRoute('app_survey_confirm', ['token' => $token]);
    }

    #[Route('/survey/confirm/{token}', name: 'app_survey_confirm', methods: ['GET'])]
    public function viewResult(Request                       $request,
                               SurveyFillUpRequestRepository $surveyFillUpRequestRepository,
                               SurveyRepository            $surveyRepository): Response
    {
        $token = $request->get('token');
        $fillUpRequest = $surveyFillUpRequestRepository->findOneBy(['hashedToken' => $token]);
        if (!$fillUpRequest) {
            throw $this->createNotFoundException('Survey fill-up request not found');
        }
        $surveyFillUpRequestRepository->remove($fillUpRequest);
        $survey = $fillUpRequest->getSurvey();
        if ($survey->getAllPollHasNoRequest()) {
            $survey->setStatus(Survey::STATUS_OVER);
            $surveyRepository->save($survey);
        }
        return $this->render('public/confirm_result.html.twig');
    }

}
