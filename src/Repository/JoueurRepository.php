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
        

    public function findBySectionGroupeName($s,$g, $n)
    {
       return $this->createQueryBuilder('j')
       ->innerJoin('j.categorie', 'g')
       ->Where('g.section LIKE :section')
       ->andWhere('g.groupe LIKE :groupe')
       ->andWhere('g.nom LIKE :nom')
       ->setParameter('groupe', "%{$g}%")
       ->setParameter('section', "%{$s}%")
       ->setParameter('nom', "%{$n}%")
       // ->orderBy('s.firstname', 'ASC')
       // ->orderBy('s.lastname', 'ASC')
       ->setMaxResults(10)
       ->getQuery()
       ->getResult()
   ;

    }
}
