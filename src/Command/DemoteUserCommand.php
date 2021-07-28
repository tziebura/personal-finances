<?php


namespace App\Command;


use App\Enum\UserRole;
use App\Repository\UserRepositoryInterface;
use App\Service\UserRoleManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DemoteUserCommand extends Command
{
    protected static $defaultName = 'app:user:demote';
    private UserRepositoryInterface $userRepository;
    private UserRoleManagerInterface $userRoleManager;

    /**
     * PromoteUserCommand constructor.
     * @param UserRepositoryInterface $userRepository
     * @param UserRoleManagerInterface $userRoleManager
     */
    public function __construct(UserRepositoryInterface $userRepository, UserRoleManagerInterface $userRoleManager, string $name = '')
    {
        $this->userRepository = $userRepository;
        $this->userRoleManager = $userRoleManager;

        parent::__construct($name);
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        do {
            $email = $io->ask('Enter username');
            $user  = $this->userRepository->findByLogin($email);

            if (!$user) {
                $io->error('Invalid username');
            }
        } while(!$user);

        do {
            $role = $io->ask('Enter role to revoke, ROLE_USER cannot be revoked. User roles are: ' . join(', ', $user->getRoles()));

            if (!in_array($role, $user->getRoles()) || $role === UserRole::ROLE_USER()->getValue()) {
                $io->error('Invalid role');
            }
        } while(!in_array($role, $user->getRoles()) || $role === UserRole::ROLE_USER()->getValue());

        $this->userRoleManager->demote($user, new UserRole($role));
        $this->userRepository->save($user);

        $io->success('User has been demoted');
        return 0;
    }
}