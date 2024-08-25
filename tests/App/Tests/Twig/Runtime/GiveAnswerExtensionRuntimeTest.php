<?php

namespace App\Tests\Twig\Runtime;

use App\Twig\Runtime\GiveAnswerExtensionRuntime;
use PHPUnit\Framework\TestCase;
use App\Service\YamlSurveyParse;

class GiveAnswerExtensionRuntimeTest extends TestCase
{
    private $surveyParseStub;
    private $runtime;

    protected function setUp(): void
    {

        $this->surveyParseStub = YamlSurveyParse::getInstance();
        $this->runtime = new GiveAnswerExtensionRuntime();
        $this->surveyParseStub->setIsTest(True);
    }

    protected function tearDown(): void
    {
        $this->surveyParseStub->setIsTest(false);
    }

    public function testGetColorBad(): void
    {
        $this->assertNotNull($this->runtime->getColorBad());
        $this->assertEquals('red', $this->runtime->getColorBad());
    }

    public function testGetColorMid() : void
    {
        $this->assertNotNull($this->runtime->getColorMid());
        $this->assertEquals('orange', $this->runtime->getColorMid());
    }

    public function testGetColorHigh() : void
    {
        $this->assertNotNull($this->runtime->getColorHigh());
        $this->assertEquals('lightgreen', $this->runtime->getColorHigh());
    }

    public function testGetPourcentRangeBad()
    {
        $this->assertNotNull($this->runtime->getPourcentRangeBad());
    }

    public function testGetConfigSurveyArray()
    {
        $this->assertNotNull($this->runtime->getConfigSurveyArray());
        $this->assertNotEquals($this->surveyParseStub->getConfig(), $this->runtime->getConfigSurveyArray());

        $this->assertNotNull($this->runtime->getConfigSurveyArray()[GiveAnswerExtensionRuntime::CONFIG_COLOR_BAD_KEY]);
        $this->assertNotNull($this->runtime->getConfigSurveyArray()[GiveAnswerExtensionRuntime::CONFIG_COLOR_MID_KEY]);
        $this->assertNotNull($this->runtime->getConfigSurveyArray()[GiveAnswerExtensionRuntime::CONFIG_COLOR_HIGH_KEY]);
        $this->assertNotNull($this->runtime->getConfigSurveyArray()[GiveAnswerExtensionRuntime::CONFIG_AVERAGE_BAD_KEY]);
        $this->assertNotNull($this->runtime->getConfigSurveyArray()[GiveAnswerExtensionRuntime::CONFIG_STEP_RATE_KEY]);
        $this->assertNotNull($this->runtime->getConfigSurveyArray()[GiveAnswerExtensionRuntime::CONFIG_MIN_RATE_KEY]);
        $this->assertNotNull($this->runtime->getConfigSurveyArray()[GiveAnswerExtensionRuntime::CONFIG_MAX_RATE_KEY]);

    }

    public function testGetQuestionFromTemplate()
    {
        $this->assertNotNull($this->runtime->getQuestionFromTemplate('1', '1'));
        $this->assertEmpty($this->runtime->getQuestionFromTemplate('100', '1'));

    }

    public function testGetTitleBlocFromTemplate() {
        $this->assertNotNull($this->runtime->getTitleBlocFromTemplate('1'));
        $this->assertEmpty($this->runtime->getTitleBlocFromTemplate('100'));
    }
    // ...continuez les autres tests avec cette approche

}