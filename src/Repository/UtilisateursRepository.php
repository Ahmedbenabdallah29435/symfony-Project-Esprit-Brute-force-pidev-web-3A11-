<?php

namespace App\Repository;

use App\Entity\Utilisateurs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Utilisateurs|null find($id, $lockMode = null, $lockVersion = null)
 * @method Utilisateurs|null findOneBy(array $criteria, array $orderBy = null)
 * @method Utilisateurs[]    findAll()
 * @method Utilisateurs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UtilisateursRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Utilisateurs::class);
    }

    // /**
    //  * @return Utilisateurs[] Returns an array of Utilisateurs objects
    //  */
    /*
   
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
    public function findByEmail($Email)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.Email = :val')
            ->setParameter('val', $Email)
            ->getQuery()
            ->getSingleResult();
    }

    /**
     * 
     */
    public function countByAge()
    {
        //$query = $this->createQueryBuilder('c')
        //->select('SUBSTRING(d.date, 1, 10) as date, COUNT(c) as count')
        //->groupBy('date')
        //;
        //return $query->getQuery()->getResult();
        $query = $this->getEntityManager()->createQuery("
           SELECT u.Age as age, COUNT(u) as count FROM App\Entity\Utilisateurs u where u.Age IS NOT NULL GROUP BY age 
       ");
        return $query->getResult();
    }
    /*
    public function CountClient()
    {
        $role = '[ROLE_ADMIN]';
        $query = $this->getEntityManager()->createQuery("
           SELECT r.role as role, COUNT(r) as count FROM App\Entity\Utilisateurs r where r.role = "+ $role +"
       ");
       return $query->getResult();
    }*/

    /*
    public function findOneBySomeField($value): ?Utilisateurs
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
