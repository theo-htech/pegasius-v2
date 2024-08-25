<?php

namespace App\Tests\Entity;

use App\Entity\Answer;
use App\Entity\Poll;
use PHPUnit\Framework\TestCase;

class AnswerTest extends TestCase
{
    public function testGetId(): void
    {
        $answer = new Answer();
        $this->assertNull($answer->getId());
    }

    public function testPoll(): void
    {
        $answer = new Answer();
        $poll = new Poll();

        $answer->setPoll($poll);
        $this->assertSame($poll, $answer->getPoll());
    }

    public function testQuestion(): void
    {
        $answer = new Answer();
        $question = 'How are you?';

        $answer->setQuestion($question);
        $this->assertEquals($question, $answer->getQuestion());
    }

    public function testResponse(): void
    {
        $answer = new Answer();
        $response = 1;

        $answer->setResponse($response);
        $this->assertEquals($response, $answer->getResponse());
    }
}