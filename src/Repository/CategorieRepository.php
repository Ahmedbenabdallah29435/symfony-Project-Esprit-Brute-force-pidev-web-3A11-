<?php


namespace App\Repository;


use App\Entity\Categorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\DBALException;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Locations;
use Doctrine\DBAL\Driver\Exception;
use phpDocumentor\Reflection\Types\Null_;

/**
 * @method Categorie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Categorie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Categorie[]    findAll()
 * @method Categorie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Categorie::class);
    }

    public function SearchAndTriCategorie($ValueTyped,$order){
        $em = $this->getEntityManager();
        if($order=='DESC') {
            $query = $em->createQuery(
                'SELECT r FROM App\Entity\Categorie r   where r.nomcategorie like :ValueTyped  or r.genre like :ValueTyped order by r.idcategorie DESC '
            );
            $query->setParameter('ValueTyped', $ValueTyped . '%');
        }
        else{
            $query = $em->createQuery(
                'SELECT r FROM App\Entity\Categorie r   where r.nomcategorie like :ValueTyped  or r.genre like :ValueTyped order by r.idcategorie ASC '
            );
            $query->setParameter('ValueTyped', $ValueTyped . '%');
        }
        return $query->getResult();
    }


}