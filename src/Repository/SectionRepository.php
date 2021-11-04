<?php

namespace App\Repository;

use App\Entity\Section;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Section|null find($id, $lockMode = null, $lockVersion = null)
 * @method Section|null findOneBy(array $criteria, array $orderBy = null)
 * @method Section[]    findAll()
 * @method Section[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SectionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Section::class);
    }

    /**
     * @return Section[] Returns an array of Section objects
     */
    public function findByJoueur($value)
    {
        return $this->createQueryBuilder('s')
        ->innerJoin('s.joueurs', 'j')
        ->andWhere('j.id = :val')
            ->setParameter('val', $value)
            // ->orderBy('s.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    public function findByCategorie($value)
    {
        return $this->createQueryBuilder('s')
        ->innerJoin('s.categories', 'c')
        ->andWhere('c.id = :val')
        ->setParameter('val', $value)
        ->orderBy('c.id', 'ASC')
        ->getQuery()
        ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Section
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
