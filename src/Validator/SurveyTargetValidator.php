<?php

namespace App\Validator;


use App\Entity\Poll;
use App\Entity\SurveyTarget;

class SurveyTargetValidator
{
    /**
     * Checks if all target types are present in the given survey targets.
     *
     * @param array $surveyTargets An array of survey targets.
     * @return bool Returns true if both manager target and salary target are present in the survey targets,
     * false otherwise.
     */
    public function hasAllType($surveyTargets): bool
    {
        $hasManager = false;
        $hasSalary = false;
        foreach ($surveyTargets as $target) {
            if ($target->getTargetType() === Poll::MANAGER) {
                $hasManager = true;
            }
            if ($target->getTargetType() === Poll::SALARY) {
                $hasSalary = true;
            }
            if ($hasManager && $hasSalary) {
                // Si nous avons déjà trouvé un manager et un salary,
                // il n'y a pas besoin de continuer à itérer sur le reste du tableau.
                break;
            }
        }
        return $hasManager && $hasSalary;

    }

    /**
     * Checks if all survey targets have unique email addresses.
     *
     * @param array $surveyTargets An array of survey targets.
     * @return bool Returns true if all survey targets have unique email addresses,
     * false otherwise.
     */
    public function hasSingleEmail($surveyTargets): bool
    {
        $emails = [];
        foreach ($surveyTargets as $target) {
            $email = $target->getEmail();
            if (in_array($email, $emails)) {
                return false;
            }
            $emails[] = $email;
        }
        return true;
    }
}
