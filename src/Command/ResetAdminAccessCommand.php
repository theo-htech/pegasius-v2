<?php

namespace App\Command;

use App\Repository\UserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


#[AsCommand(
    name: 'app:reset-admin-access',
    description: 'Reset admin access give by user, during last hour',
)]
class ResetAdminAccessCommand extends Command
{
    public function __construct(private UserRepository $userRepository)
    {
        parent::__construct();
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {


        $result = $this->userRepository->findAllUserWithAdminAccess();
        $now = new \DateTime('now');


        foreach ($result as $user) {
            $interval = $user->getLastAdminAccess()->diff($now);
            if ($interval->format('%h') >= 1
                && $interval->format('%R') == '+') {
                $user->setAdminCanSee(false);
            }
            $this->userRepository->save($user);
        }
        return Command::SUCCESS;
    }
}
