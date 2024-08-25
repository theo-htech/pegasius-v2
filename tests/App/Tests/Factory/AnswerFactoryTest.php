<?php

namespace App\Tests\Factory;

use App\Entity\Answer;
use App\Factory\AnswerFactory;
use App\Service\YamlSurveyParse;
use PHPUnit\Framework\TestCase;

class AnswerFactoryTest extends TestCase
{
    private YamlSurveyParse $yamlParsor;


    protected function setUp(): void
    {
        $this->yamlParsor = YamlSurveyParse::getInstance();
        $this->yamlParsor->setIsTest(true);
    }

    protected function tearDown(): void
    {
        $this->yamlParsor->setIsTest(false);
    }
    public function testCreateAllAnswers()
    {
        $answers = AnswerFactory::createAllAnswers();

        $this->assertIsArray($answers);

        // Vérifier qu'il y a au moins une réponse
        $this->assertGreaterThanOrEqual(1, count($answers));

        // Vérifier que tous les éléments du tableau sont des instances de Answer
        foreach ($answers as $answer) {
            $this->assertInstanceOf(Answer::class, $answer);
            $this->assertNotEmpty($answer->getQuestion());
            $this->assertNotEmpty($answer->getBloc());
        }
    }
}