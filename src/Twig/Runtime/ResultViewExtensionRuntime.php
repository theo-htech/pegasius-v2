<?php

namespace App\Twig\Runtime;

use App\Entity\Answer;
use App\Repository\AnswerRepository;
use App\Service\YamlSurveyParse;
use QuickChart;
use Twig\Extension\RuntimeExtensionInterface;

class ResultViewExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct(private readonly YamlSurveyParse  $surveyParse,
                                private readonly AnswerRepository $answerRepository)
    {
        // Inject dependencies if needed
    }

    public function getQuestion($question, $bloc)
    {
        return $this->surveyParse->getQuestionLineBlock($question, $bloc);
    }

    public function getBloc($bloc)
    {
        return $this->surveyParse->getTitleBlock($bloc);
    }

    /**
     * @param $salaryResponses
     * @param $bloc
     * @param $question
     * @return void
     */
    public function getSalaryResponseByBlocAndQuestion(
        $salaryResponses,
        $bloc,
        $question)
    {
        foreach ($salaryResponses as $answer) {
            if ($answer['bloc'] == $bloc && $answer['question'] == $question) {
                return $answer['response'];
            }
        }
        return null;
    }

    public function getColor($response)
    {
        if (!is_numeric($response)) {
            return null;
        }
        $value = floatval($response);
        if ($value < $this->surveyParse->getRateLimitLow()) {
            return $this->surveyParse->getRatingColorLow();
        } elseif ($this->surveyParse->getRateLimitLow() <= $value
            && $value < $this->surveyParse->getRateLimitMid()) {
            return $this->surveyParse->getRatingColorMid();
        } else {
            return $this->surveyParse->getRatingColorHigh();
        }
    }

    public function getBlocTitles()
    {
        return implode(",", $this->surveyParse->getAllBlocTitle());
    }

    function calculateAverage(array $data)
    {
        $blocData = [];
        $blocCounts = [];

        foreach ($data as $item) {
            $bloc = $item['bloc'];
            $response = $item['response'];

            if (!isset($blocData[$bloc])) {
                $blocData[$bloc] = 0;
                $blocCounts[$bloc] = 0;
            }

            $blocData[$bloc] += $response;
            $blocCounts[$bloc]++;
        }

        $averages = [];
        foreach ($blocData as $bloc => $total) {
            $averages[$bloc] = round($total / $blocCounts[$bloc], 2);
        }

        return implode(",", $averages);
    }

    public function dataChartRadar($managerPoll, $salaryPoll, $ios)
    {
        $config = "{
        type: 'radar',
        data: {
            labels: ['" . implode("','", $this->surveyParse->getAllBlocTitle()) . "'],
            datasets: [{
                label: 'Managers',
                data: [" . $this->calculateAverage($managerPoll) . "],
                fill: true,
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgb(255, 99, 132)',
                pointBackgroundColor: 'rgb(255, 99, 132)',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: 'rgb(255, 99, 132)'
            }, {
                label: 'Equipiers',
                data: [" . $this->calculateAverage($salaryPoll) . "],
                fill: true,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgb(54, 162, 235)',
                pointBackgroundColor: 'rgb(54, 162, 235)',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: 'rgb(54, 162, 235)'
            }]
        }}";
        $chart = new QuickChart(array('width' => 1080,
            'height' => 720,
            'version' => '4',
            "format"=> "png",
        ));
        if ($ios == 1) {

            $chart->setDevicePixelRatio(2);
        }
        $chart->setConfig($config);

        return $chart->getShortUrl();
    }

    public function calculateAverageByBloc($poll, $bloc): float
    {
        $average = 0;
        $count = 0;

        foreach ($poll as $item) {
            if ($item['bloc'] == $bloc) {
                $average += $item['response'];
                $count++;
            }
        }

        if ($count > 0) {
            $average /= $count;
        }

        return round($average, 2);
    }

    public function getQuestionsForBloc($poll, $bloc)
    {
        $questions = [];
        foreach ($poll as $item) {
            if ($item['bloc'] == $bloc) {
                $questions[] = array(
                    'question' => $item['question'],
                    'response' => $item['response']
                );
            }
        }
        return $questions;
    }


    public function dataChartRadarByBloc($managerPoll, $salaryPoll, $bloc, $ios)
    {
        $questionNumber = [];
        $managerResponses = [];
        $salaryResponses = [];

        foreach ($managerPoll as $item) {
            if ($item['bloc'] == $bloc) {
               $questionNumber[] = $item['question'];
               $managerResponses[] = $item['response'];
            }
        }

        foreach ($questionNumber as $number) {
            $salaryResponses[] = $this->getSalaryResponseByBlocAndQuestion($salaryPoll, $bloc, $number);
        }


        $config = "{
        type: 'radar',
        data: {
            labels: ['" . implode("','", $questionNumber) . "'],
            datasets: [{
                label: 'Managers',
                data: [" . implode(',', $managerResponses) . "],
                fill: true,
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgb(255, 99, 132)',
                pointBackgroundColor: 'rgb(255, 99, 132)',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: 'rgb(255, 99, 132)'
            }, {
                label: 'Equipiers',
                data: [" . implode(',', $salaryResponses) . "],
                fill: true,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgb(54, 162, 235)',
                pointBackgroundColor: 'rgb(54, 162, 235)',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: 'rgb(54, 162, 235)'
            }]
        }}";
        $chart = new QuickChart(array('width' => 1080,
            'height' => 720,
            'version' => '4',
            "format"=> "png",
        ));
        if ($ios == 1) {

            $chart->setDevicePixelRatio(2);
        }

        $chart->setConfig($config);

        return $chart->getShortUrl();
    }
}
