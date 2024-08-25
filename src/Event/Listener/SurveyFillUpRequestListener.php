<?php

namespace App\Event\Listener;

use App\Entity\Survey;
use App\Service\EmailService;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use App\Entity\SurveyFillUpRequest;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class SurveyFillUpRequestListener
{
    public function __construct(private EmailService $emailService)
    {

    }

    /**
     * @throws TransportExceptionInterface
     */
    public function postPersist(LifecycleEventArgs $eventArgs): void
    {
        $entity = $eventArgs->getObject();
        if ($entity instanceof SurveyFillUpRequest) {
            $survey = $entity->getSurvey();
            $to = $entity->getEmail();
            if ($survey->getCreator()->getEmail() != $to) {
                $this->emailService->sendSurveyRequest($to, $survey, $entity->getHashedToken());
            }
        }
    }

    public function postUpdate(LifecycleEventArgs $eventArgs)
    {
        // Ajoutez ici votre logique après l'enregistrement (persist) de l'entité SurveyFillUpRequest
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function preRemove(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getObject();
        if ($entity instanceof SurveyFillUpRequest) {
            $survey = $entity->getSurvey();
            $to = $entity->getEmail();
            if ($survey->getStatus() != Survey::STATUS_OVER) {
                $this->emailService->sendSurveyRequestConfirm($to);
            } else {
                $this->emailService->sendSurveyRequestDelete($to);
            }
        }
    }
}
