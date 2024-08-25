<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\SurveyStatusExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class SurveyStatusExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('filter_name', [SurveyStatusExtensionRuntime::class, 'doSomething']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('status_class_tab', [SurveyStatusExtensionRuntime::class, 'getStatusClassTab']),
            new TwigFunction('status_trans', [SurveyStatusExtensionRuntime::class, 'getTransStatus']),
            new TwigFunction('status_class', [SurveyStatusExtensionRuntime::class, 'getStatusClass']),


        ];
    }
}
