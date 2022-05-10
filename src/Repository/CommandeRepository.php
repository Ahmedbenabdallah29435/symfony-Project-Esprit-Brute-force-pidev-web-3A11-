<?php

namespace App\Repository;

use App\Entity\Commande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Commande|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commande|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commande[]    findAll()
 * @method Commande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commande::class);
    }

    // /**
    //  * @return Commande[] Returns an array of Commande objects
    //  */
    public function findByPhone($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.phone = :val')
            ->setParameter('val', $value)
            ->orderBy('c.phone', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    
    public function findByNom($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.nomClient = :val')
            ->setParameter('val', $value)
            ->orderBy('c.nomClient', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    public function countByDate(){
        //$query = $this->createQueryBuilder('c')
            //->select('SUBSTRING(d.date, 1, 10) as date, COUNT(c) as count')
            //->groupBy('date')
        //;
        //return $query->getQuery()->getResult();
        $query = $this->getEntityManager()->createQuery("
        SELECT SUBSTRING(a.dateCommande, 1, 10) as dateCommande, COUNT(a) as count FROM App\Entity\Commande a GROUP BY dateCommande
    ");
       return $query->getResult();
   }

     

    /*
    public function findOneBySomeField($value): ?Commande
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