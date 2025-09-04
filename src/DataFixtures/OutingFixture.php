<?php

namespace App\DataFixtures;


use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Location;
use App\Entity\Outing;
use App\Entity\Status;
use App\Entity\Utilisateur;
use App\Repository\OutingRepository;
use App\Repository\StatusRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class OutingFixture extends Fixture implements DependentFixtureInterface
{

    private $passwordHasher;
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }


    public function load(ObjectManager $manager ): void
    {

        $campus = $manager->getRepository(Campus::class)->findOneBy(['name' => 'Nantes']);

        for ($i = 0; $i < 20; $i++) {
            $user= new Utilisateur();
            $sortie = new Outing();
            $status = new Status();
            $location = new Location();
            $city = new City();

            $status->setLabel('ended');
            $manager->persist($status);

            $sortie ->setLocation($location);

            $manager->persist($status);

            $city->setName('tagranmer');
            $city->setPostalCode('91000');
            $manager->persist($city);


            $location->setName('tamer');
            $location->setCity($city);
            $location->setStreet('rue de ton pere');
            $manager->persist($location);

            $mail= 'user'. $i . '@test.com';
            $hash = $this->passwordHasher->hashPassword($user, 'root');


            $user->setFirstName('Firstname' . $i);
            $user->setLastname('lastname' . $i);
            $user->setEmail($mail );
            $user->setPhoneNumber("0689583522" . $i);
            $user->setActif(true);
            $user->setCampus($campus);
            $user->setRoles(['ROLE_ADMIN']);
            $user->setPassword($hash);
            $manager->persist($user);

            $this->addReference('status_ended'. $i, $status);



            $sortie->setName('Outing' . $i);
            $sortie->setStartDateTime(new \DateTime());
            $sortie->setDuration(20);
            $sortie->setRegistrationLimitDate(new \DateTime());
            $sortie->setNbMaxRegistration(10);
            $sortie->setOutingInfos('espaces vert' .$i);
            $sortie->setStatus($status);
            $sortie->setLocation($location);
            $sortie->setOrganizer($user);

            $manager->persist($sortie);


        }


        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }


    public function getDependencies(): array
    {
        return [
            AppFixtures::class
        ];
    }
}



