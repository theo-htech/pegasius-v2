<?php

namespace App\Command;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Notifier\Bridge\MicrosoftTeams\Action\ActionCard;
use Symfony\Component\Notifier\Bridge\MicrosoftTeams\Action\HttpPostAction;
use Symfony\Component\Notifier\Bridge\MicrosoftTeams\Action\Input\DateInput;
use Symfony\Component\Notifier\Bridge\MicrosoftTeams\Action\Input\TextInput;
use Symfony\Component\Notifier\Bridge\MicrosoftTeams\MicrosoftTeamsOptions;
use Symfony\Component\Notifier\Bridge\MicrosoftTeams\Section\Field\Fact;
use Symfony\Component\Notifier\Bridge\MicrosoftTeams\Section\Section;
use Symfony\Component\Notifier\ChatterInterface;
use Symfony\Component\Notifier\Message\ChatMessage;

#[AsCommand(
    name: 'app:test-email',
    description: 'Add a short description for your command',
)]
class TestEmailCommand extends Command
{
    public function __construct(private MailerInterface $mailer, private ChatterInterface $chatter)
    {
        parent::__construct();
    }


    /**
     * @throws TransportExceptionInterface
     * @throws \Symfony\Component\Notifier\Exception\TransportExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $email = (new TemplatedEmail())
            ->to('user@example.com')
            ->subject('Exemple d\'email')
            ->htmlTemplate('email/test_email.html.twig')
            ->context([
                'name' => 'John Doe',
            ]);
        $this->mailer->send($email);

        $io->success('Email sent');

     //   $chatMessage = new ChatMessage('');

// Action elements
//        $input = new TextInput();
//        $input->id('input_title');
//        $input->isMultiline(true)->maxLength(5)->title('In a few words, why would you like to participate?');
//
//        $inputDate = new DateInput();
//        $inputDate->title('Proposed date')->id('input_date');

//// Create Microsoft Teams MessageCard
//        $microsoftTeamsOptions = (new MicrosoftTeamsOptions())
//            ->title('Symfony Online Meeting')
//            ->text('Symfony Online Meeting are the events where the best developers meet to share experiences...')
//            ->summary('Summary')
//            ->themeColor('#F4D35E')
//            ->section((new Section())
//                ->title('Talk about Symfony 5.3 - would you like to join? Please give a shout!')
//                ->fact((new Fact())
//                    ->name('Presenter')
//                    ->value('Fabien Potencier')
//                )
//                ->fact((new Fact())
//                    ->name('Speaker')
//                    ->value('Patricia Smith')
//                )
//                ->fact((new Fact())
//                    ->name('Duration')
//                    ->value('90 min')
//                )
//                ->fact((new Fact())
//                    ->name('Date')
//                    ->value('TBA')
//                )
//            )
//            ->action((new ActionCard())
//                ->name('ActionCard')
//                ->input($input)
//                ->input($inputDate)
//                ->action((new HttpPostAction())
//                    ->name('Add comment')
//                    ->target('http://target')
//                )
//            )
//        ;
//
//// Add the custom options to the chat message and send the message
//        $chatMessage->options($microsoftTeamsOptions);
//        $this->chatter->send($chatMessage);

        return Command::SUCCESS;
    }
}
