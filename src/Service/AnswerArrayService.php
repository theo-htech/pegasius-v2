<?php

namespace App\Service;

use App\Entity\Answer;
use App\Entity\Poll;
use App\Repository\AnswerRepository;

class AnswerArrayService
{
    public function __construct(private AnswerRepository $answerRepository)
    {
    }

    public function AnswersToArray(Poll $poll)
    {
        $answersArray = [];
        $parser = YamlSurveyParse::getInstance();
        $indexBloc = 1;
        while (!empty($parser->getQuestionBlock($indexBloc))) {
            $indexQuestion = 1;
            while (!empty($parser->getQuestionLineBlock($indexQuestion, $indexBloc)))
            {
                    $answer = $this->answerRepository->findOneBy([
                        'poll' => $poll->getId(),
                        'question' => $indexQuestion,
                        'bloc' => $indexBloc
                        ]);
                    $answerArray = [
                        'question' => $answer->getQuestion(),
                        'response' => $answer->getResponse(),
                        'bloc' => $answer->getBloc(),
                    ];
                    $answersArray[] = $answerArray;
                $indexQuestion++;
            }
            $indexBloc++;
        }
        return $answersArray;
    }
}