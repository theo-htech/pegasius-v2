<?php

namespace App\Command;

use App\Entity\User;
use App\Factory\UserFactory;
use App\Repository\UserRepository;
use InvalidArgumentException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

#[AsCommand(
    name: 'app:create-first-user',
    description: 'create first user with admin right',
)]
class CreateFirstUserCommand extends Command
{
    public function __construct(private  UserRepository              $userRepository,
                                private  ContainerBagInterface       $params,
                                private  UserPasswordHasherInterface $userPasswordHasher,
                                private  MailerInterface $mailer,
                                private ResetPasswordHelperInterface $resetPasswordHelper)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'Email connexion');
    }

    /**
     * @throws ResetPasswordExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ContainerExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        try {
            $arg1 = $input->getArgument('email');
        } catch (InvalidArgumentException $exception) {
            $io->error($exception->getMessage());
            return Command::FAILURE;
        }

        if ($arg1) {
            $user = $this->userRepository->findOneByEmail($arg1);
            if ($user != null) {
                $io->error("Already user in database");
                return Command::FAILURE;
            }

            $countAdmin = $this->userRepository->findCountUsersAdmin();
            if ($countAdmin >= intval($this->params->get('app.admin_number'))) {
                $io->error("Too much admin user in database");
                return Command::FAILURE;
            }

            $user = UserFactory::createAdminUser($arg1);
            $user->setPassword(
                $this->userPasswordHasher->hashPassword(
                    $user,
                    md5(rand())
                )
            );
            $this->userRepository->save($user);
            $this->processSendingPasswordResetEmail($user);

        } else {
            $io->error('Email missing');
            return Command::INVALID;
        }


        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }

    /**
     * @throws ResetPasswordExceptionInterface
     * @throws TransportExceptionInterface
     */
    private function processSendingPasswordResetEmail($user): bool
    {
        $resetToken = $this->resetPasswordHelper->generateResetToken($user);


        $email = (new TemplatedEmail())
            ->from(new Address('no-reply@horizon-tech.eu', 'O.do.P'))
            ->to($user->getEmail())
            ->subject('Account created - Define your password')
            ->htmlTemplate('reset_password/email.html.twig')
            ->context([
                'resetToken' => $resetToken,
            ])
        ;

        $this->mailer->send($email);
        return true;
    }
}
