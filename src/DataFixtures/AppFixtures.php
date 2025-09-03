<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Location;
use App\Entity\Status;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $this->addStatus($manager);
        $this->addCampus($manager);
        $this->addCity($manager);
        $this->addLocation($manager);

        $manager->flush();
    }

    public function addStatus(ObjectManager $manager): void
    {
        $facker = Factory::create('fr_FR');
        $status = [
            "Créée",
            "Ouverte",
            "Cloturée",
            "Activité en cours",
            "Passée",
            "Annulée"
        ];
        foreach ($status as $state) {
            $etat = new Status;
            $etat->setLabel($state);
            $manager->persist($etat);

        }
        $manager->flush();

    }


    public function addCampus(ObjectManager $manager): void
    {
        $facker = Factory::create('fr_FR');
        $campus = [
            "Nantes",
            "Rennes",
            "Quimper",
            "Niort",
            "Campus en ligne"
        ];
        foreach ($campus as $school) {
            $establishment = new Campus();
            $establishment->setName($school);
            $manager->persist($establishment);

        }
        $manager->flush();

    }


    public function addCity(ObjectManager $manager): void
    {
        $cities = [
            "Nantes" => "44000",
            "Rennes" => "35000",
            "Quimper" => "29000",
            "Niort" => "79000",
            "Paris" => "75000",
            "Marseille" => "13000",
            "Lyon" => "69000",
            "Toulouse" => "31000",
            "Bordeaux" => "33000",
            "Lille" => "59000",
        ];

        foreach ($cities as $name => $postalCode) {
            $city = new City();
            $city->setName($name);
            $city->setPostalCode($postalCode);
            $manager->persist($city);
        }

        $manager->flush();
    }


    public function addLocation(ObjectManager $manager): void
    {
        $cities = $manager->getRepository(City::class)->findAll();
        $faker = Factory::create('fr_FR');

        $places = [
            "Tour Eiffel",
            "Cathédrale Notre-Dame",
            "Musée du Louvre",
            "Arc de Triomphe",
            "Place de la Bourse",
            "Pont du Gard",
            "Mont Saint-Michel",
            "Château de Chambord",
            "Palais des Papes",
            "Cité de Carcassonne",
            "Vieux-Port de Marseille",
            "Parc de la Tête d'Or",
            "Capitole de Toulouse",
            "Grand-Place de Lille",
            "Cathédrale Saint-André de Bordeaux",
            "Zoo de Beauval",
            "Futuroscope",
            "Puy du Fou",
            "Parc du Thabor",
            "Château des Ducs de Bretagne",
            "Machines de l'île",
            "Château de Chenonceau",
            "Cathédrale de Reims",
            "Fort Boyard",
            "Place Bellecour",
            "Palais Garnier",
            "Pont Alexandre III",
            "Jardin du Luxembourg",
            "Basilique Notre-Dame de Fourvière",
            "Cité des Sciences et de l’Industrie",
        ];
        for($i = 0; $i <= 30; $i++) {
            $location = new Location();
            $location->setName($faker->randomElement($places));
            $location->setStreet($faker->streetAddress);
            $location->setLatitude($faker->latitude);
            $location->setLongitude($faker->longitude);
            $location->setCity($faker->randomElement($cities));
            $manager->persist($location);

        }
        $manager->flush();
    }

}
