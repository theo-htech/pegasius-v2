<?php

namespace App\Tests\App\Tests\Service;

use PHPUnit\Framework\TestCase;
use App\Service\AppVersionningParse;

class AppVersionningParseTest extends TestCase
{
    private $appVersioningParse;

    public function setUp(): void
    {
        $this->appVersioningParse = AppVersionningParse::getInstance();
        $this->appVersioningParse->setIsTest(true); // Set to testing mode
    }

    public function tearDown(): void
    {
        $this->appVersioningParse->setIsTest(false);
        unset($this->appVersioningParse);
    }

    public function testIsTest(): void
    {
        $this->assertTrue($this->appVersioningParse->isTest());
    }

    public function testParseFile(): void
    {
        $parsedFile = $this->appVersioningParse->parseFile();

        // Checks if parsedFile is an array
        $this->assertNotEmpty($parsedFile);

        // If you know the content of versionning.yaml, you can have more specific tests
        // $this->assertEquals(['APP_VERSION' => '1.0', 'APP_BRANCH' => 'master'], $parsedFile);
    }

    public function testGetVersion(): void
    {
        // Since we have no guarantee of current version,
        // we just check if it's null or a string
        $this->assertNotEmpty($this->appVersioningParse->getVersion());
    }

    public function testGetBranchKey(): void
    {
        // Since we have no guarantee of current branch,
        // we just check if it's null or a string
        $this->assertNotEmpty($this->appVersioningParse->getBranchKey());
    }
}