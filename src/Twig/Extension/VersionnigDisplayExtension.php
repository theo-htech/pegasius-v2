<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\VersionnigDisplayExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class VersionnigDisplayExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('filter_name', [VersionnigDisplayExtensionRuntime::class, 'doSomething']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_version_app', [VersionnigDisplayExtensionRuntime::class, 'getVersionApp']),
        ];
    }
}
