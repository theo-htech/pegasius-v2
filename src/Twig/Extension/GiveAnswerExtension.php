<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\GiveAnswerExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class GiveAnswerExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('filter_name', [GiveAnswerExtensionRuntime::class, 'doSomething']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_color_bad', [GiveAnswerExtensionRuntime::class, 'getColorBad']),
            new TwigFunction('get_pourcent_range_bad', [GiveAnswerExtensionRuntime::class, 'getPourcentRangeBad']),
            new TwigFunction('get_color_mid', [GiveAnswerExtensionRuntime::class, 'getColorMid']),
            new TwigFunction('get_pourcent_range_mid', [GiveAnswerExtensionRuntime::class, 'getPourcentRangeMid']),
            new TwigFunction('get_color_high', [GiveAnswerExtensionRuntime::class, 'getColorHigh']),
            new TwigFunction('get_pourcent_range_high', [GiveAnswerExtensionRuntime::class, 'getPourcentRangeHigh']),
            new TwigFunction('get_config_survey_array', [GiveAnswerExtensionRuntime::class, 'getConfigSurveyArray']),
            new TwigFunction(
                'get_question_from_template',
                [GiveAnswerExtensionRuntime::class,
                'getQuestionFromTemplate'],
            ),
            new TwigFunction('get_title_bloc_from_template', [
                GiveAnswerExtensionRuntime::class,
                'getTitleBlocFromTemplate'
            ])
        ];
    }
}
