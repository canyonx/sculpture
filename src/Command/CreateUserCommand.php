<?php

namespace App\Command;

use App\Entity\User;
use App\Entity\Setting;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * To create new user from command line :
 * symfony console app:create-user username password
 */
#[AsCommand(
    name: 'app:create-user',
    description: 'Crée un nouvel utilisateur.',
    hidden: false,
    aliases: ['app:add-user']
)]
class CreateUserCommand extends Command
{
    protected static $defaultName = 'app:create-user';
    protected static $defaultDescription = 'Creates a new user.';

    public function __construct(
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $encoder
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('username', InputArgument::REQUIRED, 'Nom d\'utilisateur')
            ->addArgument('password', InputArgument::REQUIRED, 'Mot de passe');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Création de l'utilisateur de base
        $user = new User();
        $user->setUsername($input->getArgument('username'));
        $password = $this->encoder->hashPassword($user, $input->getArgument('password'));
        $user->setPassword($password);
        $user->setRoles(['ROLE_ADMIN']);


        // Création des settings par défaut
        $setting = new Setting();
        $setting->setTitle('Titre');
        $setting->setSubTitle('Sous-titre');
        $setting->setDescription('Description du site internet');
        $setting->setLogo('logo');
        $setting->setBackground('background');

        dump($user);
        dump($setting);

        $this->em->persist($user);
        $this->em->persist($setting);
        $this->em->flush();

        return Command::SUCCESS;
    }
}
