<?php

namespace App\DataFixtures;

use App\Entity\Activity;
use App\Entity\RecreationPark;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
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
        $recreationPark->setCity('Toulouse');
        $recreationPark->setZipcode(31200);
        $recreationPark->setDescription('Viens faire du wakeboard à Toulouse');
        $recreationPark->setWebsite('https://www.wampark.fr/toulouse-sesquieres/toulouse-sesquieres-tarifs/');
        $recreationPark->addActivity($this->getReference('Wakeboard'));
        $manager->persist($recreationPark);

        $manager->flush();
    }
}
