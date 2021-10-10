<?php

namespace App\Repository;

use App\Entity\Domicile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Domicile|null find($id, $lockMode = null, $lockVersion = null)
 * @method Domicile|null findOneBy(array $criteria, array $orderBy = null)
 * @method Domicile[]    findAll()
 * @method Domicile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DomicileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Domicile::class);
    }

    // /**
    //  * @return Domicile[] Returns an array of Domicile objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Domicile
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
