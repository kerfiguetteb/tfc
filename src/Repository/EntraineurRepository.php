<?php

namespace App\Repository;

use App\Entity\Entraineur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Entraineur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Entraineur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Entraineur[]    findAll()
 * @method Entraineur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EntraineurRepository extends ServiceEntityRepository
{
    use ProfileRepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Entraineur::class);
    }

    /**
     * @return Entraineur[] Returns an array of Entraineur objects
     */
    
    

    public function findByCategorie($value)
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('c.categories' ,'e')
            ->Where('e.id = :value')
            ->setParameter('value', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
