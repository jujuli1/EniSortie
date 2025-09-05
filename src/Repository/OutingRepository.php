<?php

namespace App\Repository;

use App\Entity\Outing;
use App\Entity\Utilisateur;
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


    public function search(array $filters, ?Utilisateur $user = null): array
    {
        $qb = $this->createQueryBuilder('o')
            ->leftJoin('o.campus', 'c')->addSelect('c')
            ->leftJoin('o.location', 'l')->addSelect('l')
            ->leftJoin('o.organizer', 'u')->addSelect('u')
            ->leftJoin('o.participants', 'p')->addSelect('p');

        // Campus filter
        if (!empty($filters['campus'])) {
            $qb->andWhere('o.campus = :campus')
                ->setParameter('campus', $filters['campus']);
        }

        // Content name filter
        if (!empty($filters['NameContentWord'])) {
            $qb->andWhere('o.name LIKE :word')
                ->setParameter('word', '%' . $filters['NameContentWord'] . '%');
        }

        // Between 2 dates filter
        if (!empty($filters['startDate'])) {
            $qb->andWhere('o.startDateTime >= :startDate')
                ->setParameter('startDate', $filters['startDate']);
        }
        if (!empty($filters['endDate'])) {
            $qb->andWhere('o.startDateTime <= :endDate')
                ->setParameter('endDate', $filters['endDate']);
        }

        // organizer filter
        if (!empty($filters['isOrganizer']) && $user) {
            $qb->andWhere('o.organizer = :organizer')
                ->setParameter('organizer', $user);
        }

        // participant filter
        if (!empty($filters['isParticipant']) && $user) {
            $qb->andWhere(':me MEMBER OF o.participants')
                ->setParameter('me', $user);
        }

        if (!empty($filters['isNotParticipant']) && $user) {
            $qb->andWhere(':me NOT MEMBER OF o.participants')
                ->setParameter('me', $user);
        }

        // passed filter
        if (!empty($filters['isPassed'])) {
            $qb->andWhere('o.startDateTime < :now')
                ->setParameter('now', new \DateTime());
        }

        return $qb->getQuery()->getResult();
    }

}











