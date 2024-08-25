<?php

namespace App\Tests\Validator;

use App\Factory\SurveyTargetFactory;
use App\Validator\SurveyTargetValidator;
use PHPUnit\Framework\TestCase;
use App\Entity\Poll;
use App\Entity\SurveyTarget;

class SurveyTargetValidatorTest extends TestCase
{
    private SurveyTargetValidator $surveyTargetValidator;

    protected function setUp(): void
    {
        $this->surveyTargetValidator = new SurveyTargetValidator();
    }

    public function testHasAllType()
    {
        $surveyTargets = [
            [
                SurveyTarget::TARGET_TYPE => Poll::MANAGER,
                SurveyTarget::EMAIL => 'manager@example.com'
            ],
            [
                SurveyTarget::TARGET_TYPE => Poll::SALARY,
                SurveyTarget::EMAIL => 'salary@example.com'
            ]
        ];
        $array = SurveyTargetFactory::createFromArray($surveyTargets);

        $this->assertTrue($this->surveyTargetValidator->hasAllType($array));

        $surveyTargets = [
            [
                SurveyTarget::TARGET_TYPE => Poll::MANAGER,
                SurveyTarget::EMAIL => 'manager@example.com'
            ],
            [
                SurveyTarget::TARGET_TYPE => Poll::MANAGER,
                SurveyTarget::EMAIL => 'anothermanager@example.com'
            ]
        ];
        $array = SurveyTargetFactory::createFromArray($surveyTargets);
        $this->assertFalse($this->surveyTargetValidator->hasAllType($array));
    }

    public function testHasSingleEmail()
    {
        $surveyTargets = [
            [
                SurveyTarget::TARGET_TYPE => Poll::MANAGER,
                SurveyTarget::EMAIL => 'manager@example.com'
            ],
            [
                SurveyTarget::TARGET_TYPE => Poll::SALARY,
                SurveyTarget::EMAIL => 'salary@example.com'
            ]
        ];
        $array = SurveyTargetFactory::createFromArray($surveyTargets);
        $this->assertTrue($this->surveyTargetValidator->hasSingleEmail($array));

        $surveyTargets = [
            [
                SurveyTarget::TARGET_TYPE => Poll::MANAGER,
                SurveyTarget::EMAIL => 'same@example.com'
            ],
            [
                SurveyTarget::TARGET_TYPE => Poll::SALARY,
                SurveyTarget::EMAIL => 'same@example.com'
            ]
        ];
        $array = SurveyTargetFactory::createFromArray($surveyTargets);
        $this->assertFalse($this->surveyTargetValidator->hasSingleEmail($array));
    }
}