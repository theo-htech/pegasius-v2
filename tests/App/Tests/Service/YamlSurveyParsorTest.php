<?php

namespace App\Tests\Service;

use App\Service\YamlSurveyParse;
use PHPUnit\Framework\TestCase;

class YamlSurveyParsorTest extends TestCase
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

    public function testCanParseFile(): void {
        $result = $this->yamlParsor->parseFile();
        $this->assertNotEmpty($result, "Le fichier a été parser");
    }

    public function testCanReadQuestionBlock() : void {
        $result = $this->yamlParsor->getQuestionBlock(1);
        $this->assertNotEmpty($result, 'Le bloc de question a été trouver');
    }

    public function testCantReadQuestionBlock() : void {
        $result = $this->yamlParsor->getQuestionBlock(100);
        $this->assertEmpty($result, 'Le bloc de question n\'a pas été trouvé');
    }

    public function testCanGetBlocTitle() : void {
        $result = $this->yamlParsor->getTitleBlock(1);
        $this->assertNotEmpty($result, 'Le titre du bloc de question a été trouvé');
    }

    public function testCantGetBlocTitle() : void {
        $result = $this->yamlParsor->getTitleBlock(100);
        $this->assertEmpty($result, 'Le titre du bloc de question n\'a pas été trouvé');
    }

    public function testCanGetQuestionFromExistingBloc() : void {
        $result = $this->yamlParsor->getQuestionLineBlock(1, 1);
        $this->assertNotEmpty($result, 'Question trouvée.');
    }

    public function testCantGetQuestionFromExistingBloc() : void {
        $result = $this->yamlParsor->getQuestionLineBlock(150, 1);
        $this->assertEmpty($result, 'Question non trouvée');
    }

    public function testCantGetQuestionFromNotExisitingBloc() : void {
        $result = $this->yamlParsor->getQuestionLineBlock(1, 150);
        $this->assertEmpty($result, 'Question non trouvée');
    }

    public function testCantGetQuestionFromNotExisitingBloc2() : void {
        $result = $this->yamlParsor->getQuestionLineBlock(150, 150);
        $this->assertEmpty($result, 'Question non trouvée');
    }

    public function testGetConfig() : void {
        $result = $this->yamlParsor->getConfig();
        $this->assertNotEmpty($result);
    }

    public function testGetInstance(): void
    {
        $result = YamlSurveyParse::getInstance();
        $this->assertInstanceOf(YamlSurveyParse::class, $result);
    }

    public function testGetRatingLimit() : void {
        $result = $this->yamlParsor->getRatingLimit();
        $this->assertNotEmpty($result);
    }

    public function testGetRatingLimitLow() {
        $result = $this->yamlParsor->getRatingLimitLow();
        $this->assertNotNull($result);
    }

    public function testGetRatingLimitHigh() {
        $result = $this->yamlParsor->getRatingLimitHigh();
        $this->assertNotNull($result);
    }

    public function testRatingColor() {
        $result = $this->yamlParsor->getRatingColor();
        $this->assertNotEmpty($result);
    }

    public function testGetRatingColorLow() {
        $result = $this->yamlParsor->getRatingColorLow();
        $this->assertNotNull($result);
    }

    public function testGetRatingColorMid() {
        $result = $this->yamlParsor->getRatingColorMid();
        $this->assertNotNull($result);
    }

    public function testGetRatingColorHigh() {
        $result = $this->yamlParsor->getRatingColorHigh();
        $this->assertNotNull($result);
    }

    public function testGetRateLimit() {
        $result = $this->yamlParsor->getRateLimit();
        $this->assertNotEmpty($result);
    }

    public function testGetRateLimitLow() {
        $result = $this->yamlParsor->getRateLimitLow();
        $this->assertNotNull($result);
    }

    public function testGetRateLimitMid() {
        $result = $this->yamlParsor->getRateLimitMid();
        $this->assertNotNull($result);
    }

    public function testCannotGetLimitHigh() {
        $configLimit = $this->yamlParsor->getRateLimit();
        $this->assertTrue(!isset($configLimit[YamlSurveyParse::HIGH_KEY]));
    }

    public function testGetStepRate() {
        $result = $this->yamlParsor->getRatingStep();
        $this->assertNotNull($result);
    }
}
