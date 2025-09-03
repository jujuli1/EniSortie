<?php

namespace App\DataFixtures;


use App\Entity\Outing;
use App\Entity\Status;
use App\Repository\StatusRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class OutingFixture extends Fixture implements DependentFixtureInterface
{


    public function load(ObjectManager $manager): void
    {

//        for ($i = 0; $i < 1; $i++) {
//            $sortie = new Outing();
//            $status = new Status();
//
//
//
//            $sortie->setName('Parc');
//            $sortie->setStartDateTime(new \DateTime());
//            $sortie->setDuration(20);
//            $sortie->setRegistrationLimitDate(new \DateTime());
//            $sortie->setNbMaxRegistration(10);
//            $sortie->setOutingInfos('espaces vert');
//            $sortie->setStatus($status);
//
//
//
//
//
//
//
//
//            $manager->persist($sortie);
//        }
//        // $product = new Product();
//        // $manager->persist($product);
//
//        $manager->flush();
    }

    public function getDependencies(): array
    {
       return [
         AppFixtures::class,
         UserFixture::class
       ];
    }
}
