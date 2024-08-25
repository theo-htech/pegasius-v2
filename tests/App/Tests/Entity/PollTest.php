<?php

namespace App\Tests\Entity;

use App\Entity\Poll;
use App\Entity\Survey;
use App\Entity\Answer;
use PHPUnit\Framework\TestCase;

class PollTest extends TestCase
{
    public function testGetId(): void
    {
        $poll = new Poll();
        $this->assertNull($poll->getId());
    }

    public function testSurvey(): void
    {
        $poll = new Poll();
        $survey = new Survey();

        $poll->setSurvey($survey);
        $this->assertSame($survey, $poll->getSurvey());
    }

    public function testType(): void
    {
        $poll = new Poll();
        $poll->setType(Poll::MANAGER);
        $this->assertEquals(Poll::MANAGER, $poll->getType());
    }

    public function testCountFillUp(): void
    {
        $poll = new Poll();
        $poll->setCountFillUp(1);
        $this->assertEquals(1, $poll->getCountFillUp());
    }

    public function testAddRemoveAnswer(): void
    {
        $poll = new Poll();
        $answer = new Answer();

        // Add Answer
        $poll->addAnswer($answer);
        $this->assertCount(1, $poll->getAnswers());
        $this->assertSame($answer, $poll->getAnswers()[0]);

        // Remove Answer
        $poll->removeAnswer($answer);
        $this->assertCount(0, $poll->getAnswers());
    }
}