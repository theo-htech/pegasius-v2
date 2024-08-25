<?php

namespace App\Tests\Factory;

use App\Entity\Answer;
use App\Factory\PollFactory;
use App\Entity\Poll;
use App\Service\YamlSurveyParse;
use PHPUnit\Framework\TestCase;

class PollFactoryTest extends TestCase
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
    public function testCreateManagerPoll()
    {
        $poll = PollFactory::createManagerPoll();

        $this->assertInstanceOf(Poll::class, $poll);
        $this->assertEquals(Poll::MANAGER, $poll->getType());
        $this->assertEquals(0, $poll->getCountFillUp());

        $this->testAllAnswers($poll->getAnswers());

    }

    public function testCreateSalaryPoll()
    {
        $poll = PollFactory::createSalaryPoll();

        $this->assertInstanceOf(Poll::class, $poll);
        $this->assertEquals(Poll::SALARY, $poll->getType());
        $this->assertEquals(0, $poll->getCountFillUp());

        $this->testAllAnswers($poll->getAnswers());

    }

    public function testCreatePoll()
    {
        $poll = PollFactory::createPoll(Poll::MANAGER);

        $this->assertInstanceOf(Poll::class, $poll);
        $this->assertEquals(Poll::MANAGER, $poll->getType());
        $this->assertEquals(0, $poll->getCountFillUp());

        $this->testAllAnswers($poll->getAnswers());

    }

    private  function testAllAnswers($answers): void
    {

        $this->assertNotEmpty($answers);

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