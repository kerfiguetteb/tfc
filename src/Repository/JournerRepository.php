<?php

namespace App\Repository;

use App\Entity\Journer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Journer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Journer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Journer[]    findAll()
 * @method Journer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JournerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Journer::class);
    }

    // /**
    //  * @return Journer[] Returns an array of Journer objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('j.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Journer
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
