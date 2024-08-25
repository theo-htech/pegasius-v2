<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\ResultViewExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class ResultViewExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('filter_name', [ResultViewExtensionRuntime::class, 'doSomething']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_question', [ResultViewExtensionRuntime::class, 'getQuestion']),
            new TwigFunction('get_bloc', [ResultViewExtensionRuntime::class, 'getBloc']),
            new TwigFunction('get_salary_response',
                [
                    ResultViewExtensionRuntime::class,
                    'getSalaryResponseByBlocAndQuestion'
                ]),

            new TwigFunction('get_color', [ResultViewExtensionRuntime::class, 'getColor']),
            new TwigFunction('get_bloc_titles', [ResultViewExtensionRuntime::class, 'getBlocTitles']),
            new TwigFunction('calculate_average', [ResultViewExtensionRuntime::class, 'calculateAverage']),
            new TwigFunction('data_chart_radar', [ResultViewExtensionRuntime::class, 'dataChartRadar']),
            new TwigFunction('calculate_average_by_bloc',
                [ResultViewExtensionRuntime::class, 'calculateAverageByBloc']),
            new TwigFunction('get_questions_for_bloc',
                [ResultViewExtensionRuntime::class, 'getQuestionsForBloc']),
            new TwigFunction('data_chart_radar_by_bloc', [ResultViewExtensionRuntime::class, 'dataChartRadarByBloc']),


        ];
    }
}
