<?php

namespace App\DataFixtures;


use App\Entity\Campus;
use App\Entity\Location;
use App\Entity\Outing;
use App\Entity\Status;
use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class OutingFixture extends Fixture implements DependentFixtureInterface
{


    public function load(ObjectManager $manager): void
    {

         $this->addOutings($manager);
    }

    public function addOutings(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $statuses = $manager->getRepository(Status::class)->findAll();
        $campuses = $manager->getRepository(Campus::class)->findAll();
        $locations = $manager->getRepository(Location::class)->findAll();
        $users = $manager->getRepository(Utilisateur::class)->findAll();

        for ($i = 0; $i < 200; $i++) {
            $outing = new Outing();

            // Set the name of the outing
            $outing->setName($faker->sentence(3));

            // Dates
            $startDate = $faker->dateTimeBetween('+1 days', '+11 months');
            $outing->setStartDateTime($startDate);

            $outing->setDuration($faker->numberBetween(30, 480));

            $registrationLimitDate = (clone $startDate)->modify('-' . rand(1, 30) . ' days');
            $outing->setRegistrationLimitDate($registrationLimitDate);

            // Nb inscriptions
            $outing->setNbMaxRegistration($faker->numberBetween(5, 50));

            // Infos
            $outing->setOutingInfos($faker->paragraph(3));

            // Relations
            $outing->setStatus($faker->randomElement($statuses));
            $outing->setCampus($faker->randomElement($campuses));
            $outing->setLocation($faker->randomElement($locations));
            $outing->setOrganizer($faker->randomElement($users));

            // Add some user
            $nbParticipants = $faker->numberBetween(1, 3);
            $participants = $faker->randomElements($users, $nbParticipants);
            foreach ($participants as $participant) {
                $outing->addParticipant($participant);
            }

            $manager->persist($outing);
        }

        $manager->flush();
    }


    public function getDependencies(): array
    {
       return [
         AppFixtures::class,
         UserFixture::class
       ];
    }
}
