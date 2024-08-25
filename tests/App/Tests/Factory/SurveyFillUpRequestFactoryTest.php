<?php

namespace App\Tests\Factory;

use App\Entity\Poll;
use App\Entity\SurveyFillUpRequest;
use App\Entity\SurveyTarget;
use App\Factory\SurveyFillUpRequestFactory;
use PHPUnit\Framework\TestCase;

class SurveyFillUpRequestFactoryTest extends TestCase
{
    public function testCreateSurveyRequest()
    {
        $poll = $this->createMock(Poll::class);
        $surveyTarget = $this->createMock(SurveyTarget::class);

        $surveyTarget->method('getEmail')
            ->willReturn('test@example.com');

        $surveyRequest = SurveyFillUpRequestFactory::createSurveyRequest($poll, $surveyTarget);

        // Assert the created surveyRequest is of the expected class.
        $this->assertInstanceOf(SurveyFillUpRequest::class, $surveyRequest);

        // Assert that the values in the SurveyFillUpRequest object are what we expect.
        $this->assertSame('test@example.com', $surveyRequest->getEmail());
        $this->assertSame($poll, $surveyRequest->getPoll());

        // Assert that it contains a valid SHA-256 hashed token.
        $this->assertTrue(!empty($surveyRequest->getHashedToken()));
        $this->assertSame(64, strlen($surveyRequest->getHashedToken()));
    }
}