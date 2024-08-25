<?php

namespace App\Factory;

use App\Entity\Poll;
use App\Entity\SurveyTarget;

class SurveyTargetFactory
{
    /**
     * Creates an array of SurveyTarget objects from the given array data.
     *
     * @param array $arrayData The array data containing SurveyTarget information.
     *                        Each element should have 'email' and 'targetType' keys.
     *
     * @return array An array of SurveyTarget objects created from $arrayData.
     *               If $arrayData is empty or any element is missing 'email' or 'targetType' keys,
     *               an empty array is returned.
     */
    public static function createFromArray($arrayData)
    {
        $surveyTargets = array();
        foreach ($arrayData as $surveyTarget) {
            if (!isset( $surveyTarget[SurveyTarget::EMAIL]) || !isset( $surveyTarget[SurveyTarget::TARGET_TYPE])) {
                return array();
            }
           $surveyTargets[] = new SurveyTarget(
               $surveyTarget[SurveyTarget::EMAIL],
               $surveyTarget[SurveyTarget::TARGET_TYPE]
           );
        }
        return $surveyTargets;
    }
}