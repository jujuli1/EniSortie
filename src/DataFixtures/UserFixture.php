<?php
namespace App\DataFixtures;

use App\Entity\User;
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
    $hash = $this->passwordHasher->hashPassword($user, 'root');
    $user->setName('yajuan ');
    $user->setLastname('H');
    $user->setEmail('yajuan@test.com' );
    $user->setPassword($hash);



    //hachage password


    $user->setRoles(['ROLE_USER']);

$manager->persist($user);
}

    for ($i = 0; $i < 1; $i++) {
        $user = new User();
        $hash = $this->passwordHasher->hashPassword($user, 'root');
        $user->setName('Ange');
        $user->setLastname('Mbang');
        $user->setEmail('ange@test.com' );
        $user->setPassword($hash);

        //hachage password


        $user->setRoles(['ROLE_USER']);

        $manager->persist($user);
    }

    for ($i = 0; $i < 1; $i++) {
        $user = new User();

        $hash = $this->passwordHasher->hashPassword($user, 'root');
        $user->setName('Julien');
        $user->setLastname('Lef');
        $user->setEmail('julien@test.com' );
        $user->setPassword($hash);

        //hachage password

        $hash = $this->passwordHasher->hashPassword($user, 'test'.$i);
        $user->setRoles(['ROLE_USER']);

        $manager->persist($user);
    }

$manager->flush();
}
}