<?php

namespace App\Tests\Entity;

use App\Entity\Survey;
use App\Entity\User;
use App\Entity\Poll;
use PHPUnit\Framework\TestCase;

class SurveyTest extends TestCase
{
    public function testGetId(): void
    {
        $survey = new Survey();
        $this->assertNull($survey->getId());
    }

    public function testCreator(): void
    {
        $survey = new Survey();
        $user = new User();

        $survey->setCreator($user);
        $this->assertSame($user, $survey->getCreator());
    }

    public function testCount(): void
    {
        $survey = new Survey();
        $survey->setCount(10);
        $this->assertEquals(10, $survey->getCount());
    }

    public function testStatus(): void
    {
        $survey = new Survey();
        $survey->setStatus(Survey::STATUS_NEW);
        $this->assertEquals(Survey::STATUS_NEW, $survey->getStatus());
    }

    public function testCreatedAt(): void
    {
        $survey = new Survey();
        $date = new \DateTimeImmutable();
        $survey->setCreatedAt($date);
        $this->assertEquals($date, $survey->getCreatedAt());
    }

    public function testAddRemovePoll(): void
    {
        $survey = new Survey();
        $poll = new Poll();

        // Add Poll
        $survey->addPoll($poll);
        $this->assertCount(1, $survey->getPolls());
        $this->assertSame($poll, $survey->getPolls()[0]);

        // Remove Poll
        $survey->removePoll($poll);
        $this->assertCount(0, $survey->getPolls());
    }
}