<?php

namespace App\Service;

use Exception;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class YamlSurveyParse
{
    // Config key and path for survey yaml file
    public final const SURVEY_FILE_PATH = '../config/surveys/survey.1.yml';
    public final const SURVEY_FILE_TEST_PATH = 'config/surveys/survey.1.yml';
    public final const SURVEY_BLOCK_PREFIX = 'bloc_';
    public final const TITLE = 'title';
    public final const QUESTION_KEY_PREFIX = 'q_';
    public final const CONFIG_KEY = 'config';
    public final const HIGH_KEY = 'high';
    public final const MID_KEY = 'mid';
    public final const LOW_KEY = 'low';
    public final const COLOR_KEY = 'color';
    public final const RATING_LIMIT_KEY = 'rating_limit';
    public final const LIMIT_KEY = 'limit';
    public final const STEP_KEY = 'step';


    private static $instances = [];

    private bool $isTest = false;

    public function __clone(): void
    {
        throw new Exception("Cannot clone a singleton");
    }

    /**
     * @throws Exception
     */
    public function __wakeup(): void
    {
        throw new Exception("Cannot unserialize a singleton.");
    }

    public static function getInstance(): YamlSurveyParse
    {
        $cls = static::class;
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new static();
        }
        return self::$instances[$cls];
    }

    public function parseFile()
    {
        $fileToParse = !$this->isTest() ? self::SURVEY_FILE_PATH : self::SURVEY_FILE_TEST_PATH;
        try {
            $value = Yaml::parse(file_get_contents($fileToParse));
        } catch (ParseException $e) {
            return array();
        }
        return $value;
    }

    public function getQuestionBlock($blockNumber): mixed
    {
        $parsedFile = $this->ParseFile();
        if (!isset($parsedFile[self::SURVEY_BLOCK_PREFIX . $blockNumber])) {
            return array();
        }
        return $parsedFile[self::SURVEY_BLOCK_PREFIX . $blockNumber];
    }

    public function getTitleBlock($blockNumber)
    {
        $parsedFile = $this->ParseFile();
        if (!isset($parsedFile[self::SURVEY_BLOCK_PREFIX . $blockNumber])) {
            return "";
        }
        return $parsedFile[self::SURVEY_BLOCK_PREFIX . $blockNumber][self::TITLE];
    }

    public function isTest(): bool
    {
        return $this->isTest;
    }

    public function setIsTest(bool $isTest): void
    {
        $this->isTest = $isTest;
    }

    public function getQuestionLineBlock($questionNumber, $blocNumber)
    {
        $result = $this->getQuestionBlock($blocNumber);
        if (empty($result)) {
            return array();
        }
        if (!isset($result[self::QUESTION_KEY_PREFIX . $questionNumber])) {
            return array();
        }
        return $result[self::QUESTION_KEY_PREFIX . $questionNumber];
    }

    public function getConfig()
    {
        $parsedFile = $this->ParseFile();
        if (!isset($parsedFile[self::CONFIG_KEY])) {
            return array();
        }
        return $parsedFile[self::CONFIG_KEY];
    }

    public function getRatingLimit()
    {
        $parsedFile = $this->ParseFile();
        if (!isset($parsedFile[self::CONFIG_KEY][self::RATING_LIMIT_KEY])) {
            return array();
        }
        return $parsedFile[self::CONFIG_KEY][self::RATING_LIMIT_KEY];
    }

    public function getRatingLimitLow()
    {
        $config = $this->getRatingLimit();
        if (!isset($config[self::LOW_KEY])) {
            return null;
        }
        return $config[self::LOW_KEY];
    }

    public function getRatingLimitHigh()
    {
        $config = $this->getRatingLimit();
        if (!isset($config[self::HIGH_KEY])) {
            return null;
        }
        return $config[self::HIGH_KEY];
    }

    public function getRatingColor()
    {
        $parsedFile = $this->ParseFile();
        if (!isset($parsedFile[self::CONFIG_KEY][self::COLOR_KEY])) {
            return array();
        }
        return $parsedFile[self::CONFIG_KEY][self::COLOR_KEY];
    }

    public function getRatingColorLow()
    {
        $configColor = $this->getRatingColor();
        if (!isset($configColor[self::LOW_KEY])) {
            return null;
        }
        return $configColor[self::LOW_KEY];
    }

    public function getRatingColorMid()
    {
        $configColor = $this->getRatingColor();
        if (!isset($configColor[self::MID_KEY])) {
            return null;
        }
        return $configColor[self::MID_KEY];
    }

    public function getRatingColorHigh()
    {
        $configColor = $this->getRatingColor();
        if (!isset($configColor[self::HIGH_KEY])) {
            return null;
        }
        return $configColor[self::HIGH_KEY];
    }

    public function getRateLimit()
    {
        $parsedFile = $this->ParseFile();
        if (!isset($parsedFile[self::CONFIG_KEY][self::LIMIT_KEY])) {
            return array();
        }
        return $parsedFile[self::CONFIG_KEY][self::LIMIT_KEY];
    }

    public function getRateLimitLow()
    {
        $configLimit = $this->getRateLimit();
        if (!isset($configLimit[self::LOW_KEY])) {
            return null;
        }
        return $configLimit[self::LOW_KEY];
    }

    public function getRateLimitMid()
    {
        $configLimit = $this->getRateLimit();
        if (!isset($configLimit[self::MID_KEY])) {
            return null;
        }
        return $configLimit[self::MID_KEY];
    }

    public function getRatingStep()
    {
        $config = $this->getRatingLimit();
        if (!isset($config[self::STEP_KEY])) {
            return null;
        }
        return $config[self::STEP_KEY];
    }

    public function getAllBlocTitle()
    {
        $indexBloc = 1;
        $titles = [];
        while ($this->getTitleBlock($indexBloc) != "")
        {
            $titles[] = $this->getTitleBlock($indexBloc);
            $indexBloc++;
        }
        return $titles;
    }

}
