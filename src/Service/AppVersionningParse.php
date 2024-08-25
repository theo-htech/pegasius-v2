<?php

namespace App\Service;

use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class AppVersionningParse
{
    public final const VERSION_FILE_PATH = '../.versionning.yaml';
    public final const VERSION_FILE_TEST_PATH = '.versionning.yaml';
    public final const VERSION_KEY = 'APP_VERSION';
    public final const BRANCH_KEY = 'APP_BRANCH';

    private static $instances = [];

    private bool $isTest = false;

    public function isTest(): bool
    {
        return $this->isTest;
    }

    public function setIsTest(bool $isTest): void
    {
        $this->isTest = $isTest;
    }

    public static function getInstance(): AppVersionningParse
    {
        $cls = static::class;
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new static();
        }
        return self::$instances[$cls];
    }

    public function parseFile()
    {
        $fileToParse = !$this->isTest() ? self::VERSION_FILE_PATH : self::VERSION_FILE_TEST_PATH;
        try {
            $value = Yaml::parse(file_get_contents($fileToParse));
        } catch (ParseException $e) {
            return array();
        }
        return $value;
    }

    public function getVersion(): ?string
    {
        $parsedFile = $this->parseFile();
        if (!isset($parsedFile[self::VERSION_KEY])) {
            return null;
        }
        return $parsedFile[self::VERSION_KEY];
    }

    public function getBranchKey(): ?string
    {
        $parsedFile = $this->parseFile();
        if (!isset($parsedFile[self::BRANCH_KEY])) {
            return null;
        }
        return $parsedFile[self::BRANCH_KEY];
    }
}
