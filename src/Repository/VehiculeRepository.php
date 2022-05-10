<?php

namespace App\Repository;

use App\Entity\Vehicule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Vehicule|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vehicule|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vehicule[]    findAll()
 * @method Vehicule[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VehiculeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vehicule::class);
    }

    // /**
    //  * @return Vehicule[] Returns an array of Vehicule objects
    //  */
  
    public function findByType($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.typeVehicule = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
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
           SELECT v.typeVehicule as typeV, COUNT(v) as countV FROM App\Entity\Vehicule v GROUP BY typeV
       ");
       return $query->getResult();
   }

    /*
    public function findOneBySomeField($value): ?Vehicule
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
