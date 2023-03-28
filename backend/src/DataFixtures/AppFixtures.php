<?php

namespace App\DataFixtures;

use App\Entity\Activity;
use App\Entity\RecreationPark;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordHacher;

    public function __construct(UserPasswordHasherInterface $passwordHacher)
    {
        $this->passwordHacher = $passwordHacher;
    }

    public function load(ObjectManager $manager): void
    {
        /* Activities */
        foreach (['Kitesurf', 'Canoë', 'Wakeboard', 'Accrobranche', 'Paintball', 'Parapente'] as $activityName) {
            $activity = new Activity();
            $activity->setName($activityName);
            $this->addReference($activityName, $activity);
            $manager->persist($activity);
        }


        /* RecreationParks */
        $recreationPark = new RecreationPark();
        $recreationPark->setName('Natura Game');
        $recreationPark->setAddress('1 Rte de Gragnague');
        $recreationPark->setCity('Castelmaurou');
        $recreationPark->setZipcode(31180);
        $recreationPark->setDescription('A 15 minutes de Toulouse, passez un moment en famille ou entre amis dans notre Parc d\'aventure et faites de l\'accrobranche, un escape game ou une partie de paintball');
        $recreationPark->setWebsite('https://www.natura-game.fr/');
        $recreationPark->addActivity($this->getReference('Accrobranche'));
        $recreationPark->addActivity($this->getReference('Paintball'));
        $manager->persist($recreationPark);

        $recreationPark = new RecreationPark();
        $recreationPark->setName('Wam Park');
        $recreationPark->setAddress('Base de Loisirs Sesquières, All. des Foulques');
        $recreationPark->setCity('Rennes');
        $recreationPark->setZipcode(39000);
        $recreationPark->setDescription('Viens faire du wakeboard à Toulouse');
        $recreationPark->setWebsite('https://www.wampark.fr/toulouse-sesquieres/toulouse-sesquieres-tarifs/');
        $recreationPark->addActivity($this->getReference('Wakeboard'));
        $manager->persist($recreationPark);

        $recreationPark = new RecreationPark();
        $recreationPark->setName('Wam Park 2');
        $recreationPark->setAddress('Ici on s\éclate avec des bolides à pagaie');
        $recreationPark->setCity('Lille');
        $recreationPark->setZipcode(59000);
        $recreationPark->setDescription('Allez viens !');
        $recreationPark->setWebsite('https://www.wampark.fr/toulouse-sesquieres/toulouse-sesquieres-tarifs/');
        $recreationPark->addActivity($this->getReference('Canoë'));
        $manager->persist($recreationPark);

        $recreationPark = new RecreationPark();
        $recreationPark->setName('Wam Park 3');
        $recreationPark->setAddress('Base de Loisirs');
        $recreationPark->setCity('Lyon');
        $recreationPark->setZipcode(69000);
        $recreationPark->setDescription('Viens faire du wakeboard à Toulouse');
        $recreationPark->setWebsite('https://www.wampark.fr/toulouse-sesquieres/toulouse-sesquieres-tarifs/');
        $recreationPark->addActivity($this->getReference('Wakeboard'));
        $manager->persist($recreationPark);

        $recreationPark = new RecreationPark();
        $recreationPark->setName('Wam Park 4');
        $recreationPark->setAddress('Base de Loisirs');
        $recreationPark->setCity('Bordeaux');
        $recreationPark->setZipcode(33000);
        $recreationPark->setDescription('Viens faire du wakeboard à Toulouse');
        $recreationPark->setWebsite('https://www.wampark.fr/toulouse-sesquieres/toulouse-sesquieres-tarifs/');
        $recreationPark->addActivity($this->getReference('Wakeboard'));
        $manager->persist($recreationPark);

        $recreationPark = new RecreationPark();
        $recreationPark->setName('Wam Park 5');
        $recreationPark->setAddress('Base de Loisirs Sesquières, All. des Foulques');
        $recreationPark->setCity('Toulouse');
        $recreationPark->setZipcode(31200);
        $recreationPark->setDescription('Viens faire du wakeboard à Toulouse');
        $recreationPark->setWebsite('https://www.wampark.fr/toulouse-sesquieres/toulouse-sesquieres-tarifs/');
        $recreationPark->addActivity($this->getReference('Wakeboard'));
        $manager->persist($recreationPark);

        $recreationPark = new RecreationPark();
        $recreationPark->setName('Wam Park 6');
        $recreationPark->setAddress('rue monmartre');
        $recreationPark->setCity('Paris');
        $recreationPark->setZipcode(75000);
        $recreationPark->setDescription('Viens faire du wakeboard');
        $recreationPark->setWebsite('https://www.wampark.fr/toulouse-sesquieres/toulouse-sesquieres-tarifs/');
        $recreationPark->addActivity($this->getReference('Wakeboard'));
        $manager->persist($recreationPark);

        $recreationPark = new RecreationPark();
        $recreationPark->setName('Base de loisir');
        $recreationPark->setAddress('Base de Loisirs');
        $recreationPark->setCity('Marseille');
        $recreationPark->setZipcode(06000);
        $recreationPark->setDescription('Viens faire');
        $recreationPark->setWebsite('https://www.wampark.fr/toulouse-sesquieres/toulouse-sesquieres-tarifs/');
        $recreationPark->addActivity($this->getReference('Paintball'));
        $manager->persist($recreationPark);

        /* Users */
        $user = new User();
        $user->setUsername('guidap');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword($this->passwordHacher->hashPassword($user, 'welcome-2023'));
        $manager->persist($user);

        $manager->flush();
    }
}
