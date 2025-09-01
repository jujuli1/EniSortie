<?php
namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixture extends Fixture
{
public function load(ObjectManager $manager): void
{
// create 20 products! Bam!
for ($i = 0; $i < 20; $i++) {
$user = new User();
    $user->setName('user '.$i);
    $user->setLastname('user '.$i);
    $user->setEmail('test@test.com '.$i);
$manager->persist($user);
}

$manager->flush();
}
}