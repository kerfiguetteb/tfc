<?php

namespace App\Repository;

use App\Entity\Categorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Categorie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Categorie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Categorie[]    findAll()
 * @method Categorie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Categorie::class);
    }

    /**
     * @return Categorie[] Returns an array of Categorie objects
     */

     public function findByGroupeAndsection($g, $s)
     {
        return $this->createQueryBuilder('c')
        ->where('c.groupe LIKE :g')
        ->andWhere('c.section LIKE :s')
        ->setParameter('g', "%{$g}%")
        ->setParameter('s', "%{$s}%")
        // ->orderBy('s.firstname', 'ASC')
        // ->orderBy('s.lastname', 'ASC')
        ->setMaxResults(10)
        ->getQuery()
        ->getResult()
    ;

     }


    /*
    public function findOneBySomeField($value): ?Categorie
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
