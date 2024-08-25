<?php

namespace App\Tests\Twig\Runtime;

use App\Twig\Runtime\SurveyStatusExtensionRuntime;
use App\Entity\Survey;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Translation\TranslatorInterface;

class SurveyStatusExtensionRuntimeTest extends TestCase
{
    private $translatorStub;
    private $surveyStatusExtensionRuntime;

    protected function setUp(): void
    {
        // Créez un stub pour le service TranslatorInterface
        $this->translatorStub = $this->createStub(TranslatorInterface::class);

        $this->surveyStatusExtensionRuntime = new SurveyStatusExtensionRuntime($this->translatorStub);
    }

    public function testGetStatusClassTab(): void
    {
        // Testez la méthode pour plusieurs valeurs de status
        $this->assertEquals('badge bg-success', $this->surveyStatusExtensionRuntime->getStatusClassTab(Survey::STATUS_CONFIRM));
        $this->assertEquals('badge bg-info', $this->surveyStatusExtensionRuntime->getStatusClassTab(Survey::STATUS_OVER));
        $this->assertEquals('badge bg-info', $this->surveyStatusExtensionRuntime->getStatusClassTab(Survey::STATUS_IDLE));
        $this->assertEquals('badge bg-secondary', $this->surveyStatusExtensionRuntime->getStatusClassTab(Survey::STATUS_ONGOING));
        $this->assertEquals('badge bg-danger', $this->surveyStatusExtensionRuntime->getStatusClassTab(Survey::STATUS_CANCEL));
        $this->assertEquals('badge bg-primary', $this->surveyStatusExtensionRuntime->getStatusClassTab('STATUS_NONEXISTANT'));
    }

    public function testGetStatusClass(): void
    {
        // Testez la méthode pour plusieurs valeurs de status
        $this->assertEquals('btn btn-success', $this->surveyStatusExtensionRuntime->getStatusClass(Survey::STATUS_CONFIRM));
        $this->assertEquals('btn btn-info', $this->surveyStatusExtensionRuntime->getStatusClass(Survey::STATUS_OVER));
        $this->assertEquals('btn btn-info', $this->surveyStatusExtensionRuntime->getStatusClass(Survey::STATUS_IDLE));
        $this->assertEquals('btn btn-secondary', $this->surveyStatusExtensionRuntime->getStatusClass(Survey::STATUS_ONGOING));
        $this->assertEquals('btn btn-danger', $this->surveyStatusExtensionRuntime->getStatusClass(Survey::STATUS_CANCEL));
        $this->assertEquals('btn btn-primary', $this->surveyStatusExtensionRuntime->getStatusClass('STATUS_NONEXISTANT'));
    }

    public function testGetTransStatus(): void
    {
        // Le stub va toujours retourner 'mocked translation' comme traduction
        $this->translatorStub->method('trans')->willReturn('mocked translation');

        $this->assertEquals('mocked translation', $this->surveyStatusExtensionRuntime->getTransStatus('any status'));
    }
}