<?php

namespace App\Twig\Runtime;

use App\Service\YamlSurveyParse;
use Twig\Extension\RuntimeExtensionInterface;

class GiveAnswerExtensionRuntime implements RuntimeExtensionInterface
{
    public final const CONFIG_COLOR_BAD_KEY = 'color_bad';
    public final const CONFIG_COLOR_MID_KEY = 'color_mid';
    public final const CONFIG_COLOR_HIGH_KEY = 'color_high';
    public final const CONFIG_AVERAGE_BAD_KEY = 'bad_average';
    public final const CONFIG_MIN_RATE_KEY = 'min_rate';
    public final const CONFIG_MAX_RATE_KEY = 'max_rate';
    public final const CONFIG_STEP_RATE_KEY = 'step_rate';
    public function __construct()
    {
        // Inject dependencies if needed
    }

    public function getColorBad()
    {
       $yamlSurveyParse = YamlSurveyParse::getInstance();
       return !is_null($yamlSurveyParse->getRatingColorLow()) ?
           $yamlSurveyParse->getRatingColorLow() :
           'red';
    }

    public function getPourcentRangeBad()
    {
        $yamlSurveyParse = YamlSurveyParse::getInstance();
        $valueMin = $yamlSurveyParse->getRatingLimitLow();
        $valueMax = $yamlSurveyParse->getRatingLimitHigh();
        $value = $yamlSurveyParse->getRateLimitLow();

        $percentage = (($value - $valueMin) / ($valueMax - $valueMin)) * 100;
        return number_format($percentage, 0);

    }

    public function getColorMid()
    {
        $yamlSurveyParse = YamlSurveyParse::getInstance();
        return !is_null($yamlSurveyParse->getRatingColorMid()) ?
            $yamlSurveyParse->getRatingColorMid() :
            'orange';
    }

    public function getPourcentRangeMid()
    {
        $yamlSurveyParse = YamlSurveyParse::getInstance();
        $valueMin = $yamlSurveyParse->getRatingLimitLow();
        $valueMax = $yamlSurveyParse->getRatingLimitHigh();
        $valueLow = $yamlSurveyParse->getRateLimitLow();
        $valueMid = $yamlSurveyParse->getRateLimitMid();

        $percentageLow = (($valueLow - $valueMin) / ($valueMax - $valueMin)) * 100;
        $resultLow = number_format($percentageLow, 2);

        $percentageMid = (($valueMid - $valueMin) / ($valueMax - $valueMin)) * 100;
        $resultMid = number_format($percentageMid, 2);
        $result =  $resultMid - $resultLow;
        $result = number_format($result, 0);
        return $result;
    }

    public function getColorHigh()
    {
        $yamlSurveyParse = YamlSurveyParse::getInstance();
        return  !is_null($yamlSurveyParse->getRatingColorHigh()) ?
            $yamlSurveyParse->getRatingColorHigh() :
            'green';
    }

    public function getMinRate() {
        $yamlSurveyParse = YamlSurveyParse::getInstance();
        return $yamlSurveyParse->getRatingLimitLow();
    }

    public function getMaxRate() {
        $yamlSurveyParse = YamlSurveyParse::getInstance();
        return $yamlSurveyParse->getRatingLimitHigh();
    }

    public function getStepRate() {
        $yamlSurveyParse = YamlSurveyParse::getInstance();
        return $yamlSurveyParse->getRatingStep();
    }
    /**
     * Retrieves the configuration array.
     *
     * This method returns an array containing the configuration values
     * for the keys CONFIG_AVERAGE_BAD_KEY, CONFIG_COLOR_BAD_KEY,
     * CONFIG_COLOR_MID_KEY, and CONFIG_COLOR_HIGH_KEY. The values are
     * obtained by calling the corresponding methods getPourcentRangeBad(),
     * getColorBad(), getColorMid(), and getColorHigh().
     *
     * @return array The configuration array with the following keys:
     *   - CONFIG_AVERAGE_BAD_KEY: The value returned by getPourcentRangeBad().
     *   - CONFIG_COLOR_BAD_KEY: The value returned by getColorBad().
     *   - CONFIG_COLOR_MID_KEY: The value returned by getColorMid().
     *   - CONFIG_COLOR_HIGH_KEY: The value returned by getColorHigh().
     */
    public function getConfigSurveyArray()
    {
        $config = array();
        $config[self::CONFIG_AVERAGE_BAD_KEY] = $this->getPourcentRangeBad();
        $config[self::CONFIG_COLOR_BAD_KEY] = $this->getColorBad();
        $config[self::CONFIG_COLOR_MID_KEY] = $this->getColorMid();
        $config[self::CONFIG_COLOR_HIGH_KEY] = $this->getColorHigh();
        $config[self::CONFIG_MIN_RATE_KEY] = $this->getMinRate();
        $config[self::CONFIG_MAX_RATE_KEY] = $this->getMaxRate();
        $config[self::CONFIG_STEP_RATE_KEY] = $this->getStepRate();
        return $config;
    }

    public function getQuestionFromTemplate($bloc, $question)
    {
        $yamlSurveyParse = YamlSurveyParse::getInstance();
        return $yamlSurveyParse->getQuestionLineBlock($question, $bloc);
    }

    public function getTitleBlocFromTemplate($bloc) {
        $yamlSurveyParse = YamlSurveyParse::getInstance();
        return $yamlSurveyParse->getTitleBlock($bloc);
    }
}
