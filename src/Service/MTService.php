<?php

namespace App\Service;

use App\Entity\Survey;
use App\Twig\Runtime\UserDisplayExtensionRuntime;
use Symfony\Component\Notifier\Bridge\MicrosoftTeams\MicrosoftTeamsOptions;
use Symfony\Component\Notifier\Bridge\MicrosoftTeams\Section\Field\Fact;
use Symfony\Component\Notifier\Bridge\MicrosoftTeams\Section\Section;
use Symfony\Component\Notifier\ChatterInterface;
use Symfony\Component\Notifier\Exception\TransportExceptionInterface;
use Symfony\Component\Notifier\Message\ChatMessage;
use Symfony\Contracts\Translation\TranslatorInterface;

class MTService
{
    public function __construct(private ChatterInterface $chatter,
                                private TranslatorInterface $translator,
                                private UserDisplayExtensionRuntime $userDisplay)
    {
    }


    /**
     * @throws TransportExceptionInterface
     */
    public function sendToTeams(MicrosoftTeamsOptions $microsoftTeamsOptions)
    {
        $chatMessage = new ChatMessage('');
        $chatMessage->options($microsoftTeamsOptions);
        $this->chatter->send($chatMessage);
    }

  public function notifNewSurvey(Survey $survey) {
      $microsoftTeamsOptions = (new MicrosoftTeamsOptions())
            ->title($this->translator->trans('mt.notif.survey.new.title'))
            ->summary($this->translator->trans('mt.notif.survey.new.summary'))
            ->themeColor('#F4D35E')
            ->section((new Section())
                ->title($this->translator->trans('mt.notif.survey.new.detail'))
                ->fact((new Fact())
                    ->name($this->translator->trans('mt.notif.survey.new.creator'))
                    ->value($this->userDisplay->displayUser($survey->getCreator()))
                )
                ->fact((new Fact())
                    ->name($this->translator->trans('mt.notif.survey.new.licence'))
                    ->value($survey->getCount())
                )
                ->fact((new Fact())
                    ->name($this->translator->trans('mt.notif.survey.new.survey_title'))
                    ->value($survey->getTitle())
                )
            )
        ;
      $this->sendToTeams($microsoftTeamsOptions);
  }

  public function notifCancelSurvey(Survey $survey)
  {
      $microsoftTeamsOptions = (new MicrosoftTeamsOptions())
          ->title($this->translator->trans('mt.notif.survey.cancel.title'))
          ->summary($this->translator->trans('mt.notif.survey.cancel.summary'))
          ->themeColor('#FF0000')
          ->section((new Section())
              ->title($this->translator->trans('mt.notif.survey.cancel.detail'))
              ->fact((new Fact())
                  ->name($this->translator->trans('mt.notif.survey.cancel.creator'))
                  ->value($this->userDisplay->displayUser($survey->getCreator()))
              )
              ->fact((new Fact())
                  ->name($this->translator->trans('mt.notif.survey.cancel.survey_title'))
                  ->value($survey->getTitle())
              )
          );
      $this->sendToTeams($microsoftTeamsOptions);
  }
}
