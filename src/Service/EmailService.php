<?php

namespace App\Service;

use App\Entity\Survey;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Contracts\Translation\TranslatorInterface;


class EmailService
{
    private $mailer;

    private $translator;

    public function __construct(MailerInterface $mailer,
                                TranslatorInterface $translator)
    {
        $this->mailer = $mailer;
        $this->translator = $translator;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendSurveyRequest($to, Survey $survey, $requestToken)
    {
        $email = (new TemplatedEmail())
            ->to($to)
            ->subject($this->translator->trans('email.survey_request.title'))
            ->htmlTemplate('email/survey_request.html.twig')
            ->context([
                'creator' => $survey->getCreator(),
                'requestToken' => $requestToken
            ]);
        $this->mailer->send($email);
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendSurveyRequestConfirm($to)
    {
        $email = (new TemplatedEmail())
            ->to($to)
            ->subject($this->translator->trans('email.survey_request_confirm.title'))
            ->htmlTemplate('email/survey_request_confirm.html.twig');
        $this->mailer->send($email);
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendSurveyRequestDelete($to)
    {
        $email = (new TemplatedEmail())
            ->to($to)
            ->subject($this->translator->trans('email.survey_request_delete.title'))
            ->htmlTemplate('email/survey_request_delete.html.twig');
        $this->mailer->send($email);
    }
}