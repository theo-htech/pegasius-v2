<?php

namespace App\Twig\Runtime;

use App\Entity\User;
use Twig\Extension\RuntimeExtensionInterface;

class UserDisplayExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct()
    {
        // Inject dependencies if needed
    }

    public function displayUser(User $value): ?string
    {
        $display = "";
        if ($value->getFirstName() == null ) {
            return $value->getEmail();
        }
        if ($value->getCompanyName() != null)
        {
            $display = $value->getCompanyName() . " - ";
        }
        return $display . $value->getFirstName() . ' ' . $value->getLastName();
    }
}
