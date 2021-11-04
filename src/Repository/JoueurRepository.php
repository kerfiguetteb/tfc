<?php

namespace App\Repository;

use App\Entity\Joueur;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Joueur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Joueur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Joueur[]    findAll()
 * @method Joueur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JoueurRepository extends ServiceEntityRepository
{
    use ProfileRepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Joueur::class);
    }

    /**
     * @return Joueur[] Returns an array of Joueur objects
     */
    
    public function findBySectionByCategorieByGroupe($a,$b,$c)
    {
        
        return $this->createQueryBuilder('s')
            ->innerJoin('s.section', 'js')
            ->Where('js.id = :a')
            ->setParameter('a', $a)

            ->innerJoin('s.categorie', 'jc')
            ->andWhere('jc.id = :b')
            ->setParameter('b', $b)

            ->innerJoin('s.groupe', 'jg')
            ->andWhere('jg.id = :c')
            ->setParameter('c', $c)

            ->getQuery()
            // ->setMaxResults(10)
            ->getResult()
        ;
    }
    
    public function findByCategorie($value)
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('c.categorie', 'j')
            ->andWhere('j.id = :val')
            ->setParameter('val', $value)
            ->orderBy('j.id', 'ASC')
            ->getQuery()
            // ->setMaxResults(10)
            ->getResult()
        ;
    }
    public function findBySexe($value)
    {
        return $this->createQueryBuilder('j')
            ->Where('j.sexe = :val')
            ->setParameter('val', $value)
            ->orderBy('j.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Joueur
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
