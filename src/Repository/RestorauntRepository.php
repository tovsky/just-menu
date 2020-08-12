<?php

namespace App\Repository;

use App\Entity\Restoraunt;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Restoraunt|null find($id, $lockMode = null, $lockVersion = null)
 * @method Restoraunt|null findOneBy(array $criteria, array $orderBy = null)
 * @method Restoraunt[]    findAll()
 * @method Restoraunt[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RestorauntRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Restoraunt::class);
    }

    // /**
    //  * @return Restoraunt[] Returns an array of Restoraunt objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Restoraunt
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
