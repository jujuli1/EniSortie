<?php
namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\User;
use App\Entity\Utilisateur;
use App\Entity\Uzer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixture extends Fixture
{

    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }


    public function load(ObjectManager $manager): void
    {

        for ($i = 0; $i < 1; $i++) {
            $user = new User();
            $campus = new Campus();
            $campus->setName('Nantes');
            $manager->persist($campus);
            $hash = $this->passwordHasher->hashPassword($user, 'root');
            $user->setFirstName('yajuan ');
            $user->setLastname('H');
            $user->setEmail('yajuan@test.com' );
            $user->setPhoneNumber("0689583522");
            $user->setActif(true);
            $user->setCampus($campus);
            $user->setPassword($hash);





            $user->setRoles(['ROLE_USER']);

            $manager->persist($user);
        }

        for ($i = 0; $i < 1; $i++) {
            $user = new User();
            $campus = new Campus();
            $campus->setName('Nantes');
            $manager->persist($campus);
            $hash = $this->passwordHasher->hashPassword($user, 'root');
            $user->setFirstName('Ange');
            $user->setLastname('Mbang');
            $user->setEmail('ange@test.com' );
            $user->setPhoneNumber("0689583522");
            $user->setActif(true);
            $user->setCampus($campus);
            $user->setPassword($hash);




            $user->setRoles(['ROLE_USER']);

            $manager->persist($user);
        }

        for ($i = 0; $i < 1; $i++) {
            $user = new User();

            $campus = new Campus();
            $campus->setName('Nantes');
            $manager->persist($campus);
            $hash = $this->passwordHasher->hashPassword($user, 'root');
            $user->setFirstName('Julien');
            $user->setLastname('Lef');
            $user->setEmail('julien@test.com' );
            $user->setPhoneNumber("0689583522");
            $user->setActif(true);
            $user->setCampus($campus);
            $user->setRoles(['ROLE_ADMIN']);
            $user->setPassword($hash);


            $manager->persist($user);
        }

        for ($i = 0; $i < 1; $i++) {
            $user = new Utilisateur();

            $hash = $this->passwordHasher->hashPassword($user, 'root');
            $user->setFirstName('Julien');
            $user->setLastname('Lef');
            $user->setEmail('julien@test.com' );
            $user->setPhoneNumber("0689583522");
            $user->setActif(true);
            $user->setRoles(['ROLE_ADMIN']);
            $user->setPassword($hash);


            $manager->persist($user);
        }

        for ($i = 0; $i < 1; $i++) {
            $user = new Utilisateur();

            $hash = $this->passwordHasher->hashPassword($user, 'root');
            $user->setFirstName('Ange');
            $user->setLastname('Mbang');
            $user->setEmail('ange@test.com' );
            $user->setPhoneNumber("0689583522");
            $user->setActif(true);
            $user->setPassword($hash);


            $manager->persist($user);
        }

        for ($i = 0; $i < 1; $i++) {
            $user = new Utilisateur();

            $hash = $this->passwordHasher->hashPassword($user, 'root');
            $user->setFirstName('yajuan ');
            $user->setLastname('H');
            $user->setEmail('yajuan@test.com' );
            $user->setPhoneNumber("0689583522");
            $user->setActif(true);
            $user->setPassword($hash);

            $manager->persist($user);
        }

        $manager->flush();
    }
}