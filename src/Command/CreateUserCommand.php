<?php


namespace App\Command;


use App\Form\UserRegistrationForm;
use App\Service\UserRegistrationServiceInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Form\FormFactoryInterface;

class CreateUserCommand extends Command
{
    protected static $defaultName = 'app:user:create';
    private UserRegistrationServiceInterface $userRegistrationService;
    private FormFactoryInterface $formFactory;

    public function __construct(UserRegistrationServiceInterface $userRegistrationService, FormFactoryInterface $formFactory, string $name = '')
    {
        $this->userRegistrationService = $userRegistrationService;
        $this->formFactory = $formFactory;

        parent::__construct($name);
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        do {
            $email = $io->ask('Enter user\'s email');
            $password = $io->askHidden('Enter user\'s password');

            $form = $this->formFactory->create(UserRegistrationForm::class, null, [
                'csrf_protection' => false,
            ]);
            $form->submit([
                'email' => $email,
                'password' => [
                    'first' => $password,
                    'second' => $password,
                ]
            ]);

            if ($form->isSubmitted() && !$form->isValid()) {
                $io->error('Invalid form data.');
            }

        } while ($form->isSubmitted() && !$form->isValid());

        $this->userRegistrationService->registerUser($form->getData());

        $io->success('User has been created');
        return 0;
    }
}