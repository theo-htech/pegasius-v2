<?php

namespace App\Factory;

use App\Entity\Answer;
use App\Service\YamlSurveyParse;

class AnswerFactory {
    /**
     * Creates an array of Answer objects for all questions in the survey.
     *
     * @return array An array of Answer objects.
     */
    public static function createAllAnswers(): array
    {
        $allAnswers = array();
        $yamlSurvey = YamlSurveyParse::getInstance();

        $blockNumber = 1;
            while ($yamlSurvey->getQuestionBlock($blockNumber)) {
                $questionNumber = 1;
                while ($yamlSurvey->getQuestionLineBlock($questionNumber, $blockNumber)) {
                    $answer = new Answer();
                    $answer->setBloc($blockNumber);
                    $answer->setQuestion($questionNumber);
                    $allAnswers[] = $answer;
                    $questionNumber++;
                }
                $blockNumber++;
            }
        return $allAnswers;
    }
}
