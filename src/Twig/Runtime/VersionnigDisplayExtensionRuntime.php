<?php

namespace App\Twig\Runtime;

use App\Service\AppVersionningParse;
use Twig\Extension\RuntimeExtensionInterface;

class VersionnigDisplayExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct()
    {
        // Inject dependencies if needed
    }

    public function getVersionApp()
    {
        $versionningService = AppVersionningParse::getInstance();
        return $versionningService->getVersion();
    }
}
