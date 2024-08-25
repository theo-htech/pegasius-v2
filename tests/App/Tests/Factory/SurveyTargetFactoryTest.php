<?php

namespace App\Tests\Factory;

use App\Entity\Poll;
use PHPUnit\Framework\TestCase;
use App\Factory\SurveyTargetFactory;
use App\Entity\SurveyTarget;

class SurveyTargetFactoryTest extends TestCase
{
    public function testCreateFromArray()
    {
        $arrayData = [
            [
                SurveyTarget::EMAIL => 'john.doe@example.com',
                SurveyTarget::TARGET_TYPE => Poll::MANAGER,
            ],
            [
                SurveyTarget::EMAIL => 'jane.doe@example.com',
                SurveyTarget::TARGET_TYPE => Poll::SALARY,
            ],
        ];

        $surveyTargets = SurveyTargetFactory::createFromArray($arrayData);

        $this->assertCount(2, $surveyTargets);

        $this->assertInstanceOf(SurveyTarget::class, $surveyTargets[0]);
        $this->assertEquals('john.doe@example.com', $surveyTargets[0]->getEmail());
        $this->assertEquals( Poll::MANAGER, $surveyTargets[0]->getTargetType());

        $this->assertInstanceOf(SurveyTarget::class, $surveyTargets[1]);
        $this->assertEquals('jane.doe@example.com', $surveyTargets[1]->getEmail());
        $this->assertEquals(Poll::SALARY, $surveyTargets[1]->getTargetType());
    }

    public function testCreateFromArrayWithMissingElements()
    {
        $arrayData = [
            [
                SurveyTarget::EMAIL => 'john.doe@example.com',
            ],
            [
                SurveyTarget::TARGET_TYPE => Poll::MANAGER,
            ],
        ];

        $surveyTargets = SurveyTargetFactory::createFromArray($arrayData);

        // L'array est vide car chaque élément doit avoir 'email' et 'targetType'
        $this->assertEmpty($surveyTargets);
    }

    public function testCreateFromArrayWithEmptyArray()
    {
        $arrayData = [];

        $surveyTargets = SurveyTargetFactory::createFromArray($arrayData);

        // L'array est vide car il n'y a aucune donnée pour créer les objets SurveyTarget
        $this->assertEmpty($surveyTargets);
    }
}