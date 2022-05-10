<?php

namespace App\Repository;

use App\Entity\Livraisons;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Livraisons|null find($id, $lockMode = null, $lockVersion = null)
 * @method Livraisons|null findOneBy(array $criteria, array $orderBy = null)
 * @method Livraisons[]    findAll()
 * @method Livraisons[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LivraisonsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Livraisons::class);
    }

    // /**
    //  * @return Livraisons[] Returns an array of Livraisons objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Livraisons
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
