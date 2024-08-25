<?php

namespace App\Tests\Factory;

use PHPUnit\Framework\TestCase;
use App\Factory\SurveyFactory;
use App\Entity\User;
use App\Entity\Survey;

class SurveyFactoryTest extends TestCase
{
    public function testCreateSurveyFromDashboardModal()
    {
        $title = 'Test Survey';
        $count = 10;
        $user = new User();

        $survey = SurveyFactory::createSurveyFromDashboardModal($title, $count, $user);

        $this->assertInstanceOf(Survey::class, $survey);
        $this->assertEquals($title, $survey->getTitle());
        $this->assertEquals($count, $survey->getCount());
        $this->assertEquals($user, $survey->getCreator());
    }

    // ajoutez des méthodes de test supplémentaires au besoin
}