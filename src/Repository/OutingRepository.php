<?php

namespace App\Repository;

use App\Entity\Outing;
use App\Entity\Utilisateur;
use App\Form\Model\OutingSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Outing>
 */
class OutingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Outing::class);
    }

//    /**
//     * @return Outing[] Returns an array of Outing objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Outing
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }


    public function search(OutingSearch $outingSearch, ?Utilisateur $user = null): array
    {
        // Build base query with all needed relations for filtering and display
        $qb = $this->createQueryBuilder('o')
            ->leftJoin('o.campus', 'c')->addSelect('c')
            ->leftJoin('o.location', 'l')->addSelect('l')
            ->leftJoin('o.organizer', 'u')->addSelect('u')
            ->leftJoin('o.status', 's')->addSelect('s')
            ->leftJoin('o.participants', 'p')->addSelect('p');

        // status create filter
        if ($user) {
            $qb->andWhere('s.label != :created OR o.organizer = :me')
                ->setParameter('created', 'Créée')
                ->setParameter('me', $user);
        } else {
            $qb->andWhere('s.label != :created')
                ->setParameter('created', 'Créée');
        }

        // Campus filter
        if ($outingSearch->getCampus()) {
            $qb->andWhere('o.campus = :campus')
                ->setParameter('campus', $outingSearch->getCampus());
        }

        // Content name filter
        if ($outingSearch->getNameContentWord()) {
            $qb->andWhere('o.name LIKE :word')
                ->setParameter('word', '%' . $outingSearch->getNameContentWord() . '%');
        }

        // Between 2 dates filter
        if ($outingSearch->getStartDate()) {
            $qb->andWhere('o.startDateTime >= :startDate')
                ->setParameter('startDate', $outingSearch->getStartDate());
        }
        if ($outingSearch->getEndDate()) {
            $endDate = clone $outingSearch->getEndDate();
            $endDate->setTime(23, 59, 59);
            $qb->andWhere('o.startDateTime <= :endDate')
                ->setParameter('endDate', $endDate);
        }

        // organizer filter
        if ($outingSearch->isOrganizer() && $user) {
            $qb->andWhere('o.organizer = :organizer')
                ->setParameter('organizer', $user);
        }

        // participant filter
        if ($outingSearch->isParticipant() && $user) {
            $qb->andWhere(':me MEMBER OF o.participants')
                ->setParameter('me', $user);
        }

        // Not participant filter
        if ($outingSearch->isNotParticipant() && $user) {
            $qb->andWhere(':me NOT MEMBER OF o.participants')
                ->setParameter('me', $user);
        }

        // passed filter
        if ($outingSearch->isPassed()) {
            $qb->andWhere('o.startDateTime < :now')
                ->setParameter('now', new \DateTime());
        }

        return $qb->getQuery()->getResult();
    }



}











