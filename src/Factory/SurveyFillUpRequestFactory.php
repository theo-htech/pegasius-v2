<?php

namespace App\Factory;

use App\Entity\Poll;
use App\Entity\Survey;
use App\Entity\SurveyFillUpRequest;
use App\Entity\SurveyTarget;
use Doctrine\Common\Collections\Collection;

class SurveyFillUpRequestFactory
{
    /**
     * Creates a survey request.
     *
     * @param mixed $poll The poll object.
     * @param mixed $surveyTarget The target of the survey.
     * @return SurveyFillUpRequest The created survey request.
     */
    public static function createSurveyRequest($poll, $surveyTarget)
    {


        $surveyFillUpRequest = new SurveyFillUpRequest();
        $surveyFillUpRequest->setEmail($surveyTarget->getEmail());
        $surveyFillUpRequest->setPoll($poll);
        $token = bin2hex(openssl_random_pseudo_bytes(50));
        $hashedToken = hash('sha256', $token);
        $surveyFillUpRequest->setHashedToken($hashedToken);
        return $surveyFillUpRequest;

    }
}