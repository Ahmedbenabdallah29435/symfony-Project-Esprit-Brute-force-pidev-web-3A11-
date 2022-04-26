<?php


namespace App\Repository;


use App\Entity\Joueur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\DBALException;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Locations;
use Doctrine\DBAL\Driver\Exception;
use phpDocumentor\Reflection\Types\Null_;

/**
 * @method Joueur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Joueur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Joueur[]    findAll()
 * @method Joueur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JoueurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Joueur::class);
    }


    public function SearchAndTriJoueur($ValueTyped,$order){
        $em = $this->getEntityManager();
        if($order=='DESC') {
            $query = $em->createQuery(
                'SELECT r FROM App\Entity\Joueur r   where r.nomjoueur like :ValueTyped  or r.prenomjoueur like :ValueTyped or r.datedenaissance like :ValueTyped or r.age like :ValueTyped or r.sexe like :ValueTyped or r.ville like :ValueTyped order by r.idjoueur DESC '
            );
            $query->setParameter('ValueTyped', $ValueTyped . '%');
        }
        else{
            $query = $em->createQuery(
                'SELECT r FROM App\Entity\Joueur r   where r.nomjoueur like :ValueTyped  or r.prenomjoueur like :ValueTyped or r.datedenaissance like :ValueTyped or r.age like :ValueTyped or r.sexe like :ValueTyped or r.ville like :ValueTyped order by r.idjoueur ASC '
            );
            $query->setParameter('ValueTyped', $ValueTyped . '%');
        }
        return $query->getResult();
    }

    public function find_bySexe($Sexe){

        $em = $this->getEntityManager();

        $query = $em->createQuery(
            'SELECT DISTINCT  count(r.idjoueur) FROM   App\Entity\Joueur r  where r.sexe = :sexe   '
        );
        $query->setParameter('sexe', $Sexe);
        return $query->getResult();
    }

}