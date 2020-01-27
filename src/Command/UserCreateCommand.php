<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserCreateCommand extends Command
{
    protected static $defaultName = 'app:user-create';

    protected function configure()
    {
        $this
            ->setDescription('Creates a User. Do not put anyone as an admin! ⚠️')
            ->addArgument('username', InputArgument::REQUIRED, 'username')
            ->addArgument('password', InputArgument::REQUIRED, 'password')
            ->addArgument('fullName', InputArgument::REQUIRED, 'fullName')
            ->addArgument('email', InputArgument::REQUIRED, 'email')
            ->addArgument('admin', InputArgument::OPTIONAL, 'admin', false)
            // ->addOption('admin', null, InputOption::VALUE_NONE, 'admin')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // $io = new SymfonyStyle($input, $output);


        $username = $input->getArgument('username');
        $password = $input->getArgument('password');
        $fullName = $input->getArgument('fullName');
        $email = $input->getArgument('email');
        $admin = $input->getArgument('admin');

        $entityManager = $this->getDoctrine()->getManager();
        $user = new User();

        $user->setUsername($username);
        $user->setPassword(
            $passwordEncoder->encodePassword($password)
        );
        $user->setFullName($fullName);
        $user->setAdmin($admin);
        $user->setJoinDate(new \DateTime('now'));
        $entityManager->persist($user);
        $entityManager->flush();

        $output->writeln([
        'User Creator',
        '============',
        '',
    ]);

        $output->writeln('Username: '.$input->getArgument('username'));
        $output->writeln('Password: '.$input->getArgument('password'));
        $output->writeln('Full Name: '.$input->getArgument('fullName'));
        $output->writeln('Email: '.$input->getArgument('email'));
        $output->writeln('Admin: '.$input->getArgument('admin'));

        // if ($arg1) {
        //     $io->note(sprintf('You passed an argument: %s', $arg1));
        // }

        // if ($input->getOption('option1')) {
        //     // ...
        // }

        // $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return 0;
    }
}
