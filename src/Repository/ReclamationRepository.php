<?php

namespace App\Repository;

use App\Entity\Reclamation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Reclamation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reclamation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reclamation[]    findAll()
 * @method Reclamation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReclamationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reclamation::class);
    }

    // /**
    //  * @return Reclamation[] Returns an array of Reclamation objects
    //  */
    
    public function findById($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.client = :val')
            ->setParameter('val', $value)
            ->orderBy('r.client', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByTypeHard()
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.type = :val')
            ->setParameter('val', "Hardware")
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByTypeSoft()
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.type = :val')
            ->setParameter('val', "Software")
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByTypeRep()
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.type = :val')
            ->setParameter('val', "Reparation")
            ->getQuery()
            ->getResult()
        ;
    }

    public function searchRec($str1){
        return $this->createQueryBuilder('r')
            ->where('r.type LIKE :t ')
            ->setParameter('t','%'.$str1.'%')
            ->getQuery()
            ->getResult();
    }
    
   /**
    * 
    */
    public function countByType(){
        //$query = $this->createQueryBuilder('c')
            //->select('SUBSTRING(d.date, 1, 10) as date, COUNT(c) as count')
            //->groupBy('date')
        //;
        //return $query->getQuery()->getResult();
       $query = $this->getEntityManager()->createQuery("
           SELECT r.type as type, COUNT(r) as count FROM App\Entity\Reclamation r GROUP BY type
       ");
       return $query->getResult();
   }

    /*
    public function findOneBySomeField($value): ?Reclamation
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