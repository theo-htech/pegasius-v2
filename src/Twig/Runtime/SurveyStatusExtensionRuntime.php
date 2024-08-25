<?php

namespace App\Twig\Runtime;

use App\Entity\Survey;
use Twig\Extension\RuntimeExtensionInterface;
use Symfony\Contracts\Translation\TranslatorInterface;


class SurveyStatusExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct(private TranslatorInterface $translator)
    {
        // Inject dependencies if needed
    }

    public function getStatusClassTab($value)
    {
        switch ($value) {
            case Survey::STATUS_CONFIRM:
                return 'badge bg-success';
            case Survey::STATUS_OVER:
            case Survey::STATUS_IDLE:
                return 'badge bg-info';
            case Survey::STATUS_ONGOING:
            case Survey::STATUS_MANAGER_ASKED:
                return 'badge bg-secondary';
            case Survey::STATUS_CANCEL:
                return 'badge bg-danger';
            default:
                return 'badge bg-primary';
        }
    }

    public function getStatusClass($value)
    {
        switch ($value) {
            case Survey::STATUS_CONFIRM:
                return 'btn btn-success';
            case Survey::STATUS_OVER:
            case Survey::STATUS_IDLE:
                return 'btn btn-info';
            case Survey::STATUS_ONGOING:
            case Survey::STATUS_MANAGER_ASKED:
            return 'btn btn-secondary';
            case Survey::STATUS_CANCEL:
                return 'btn btn-danger';
            default:
                return 'btn btn-primary';
        }
    }

    public function getTransStatus($value)
    {
        return $this->translator->trans('dashboard.survey_tab.tab_header.status_data.'.$value);
    }
}
