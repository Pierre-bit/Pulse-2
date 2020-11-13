<?php

namespace App\Repository;

use App\Entity\Albums;
use Doctrine\ORM\Query;
use App\Entity\AlbumsSearch;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Albums|null find($id, $lockMode = null, $lockVersion = null)
 * @method Albums|null findOneBy(array $criteria, array $orderBy = null)
 * @method Albums[]    findAll()
 * @method Albums[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AlbumsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Albums::class);
    }
    public function selectAlbums($idArtist)
    {
        return $this->createQueryBuilder('a')
            ->select('a.id','a.titre', 'a.DateSorties','a.filename', 'a.update_at','a.deezer_id')
            ->distinct(true)
            ->innerJoin('a.artiste', 'ar')
            ->where('ar.id = :id')
            ->setParameter(':id', $idArtist)
            ->orderBy('a.DateSorties', 'DESC')
            ->getQuery()
            ->getResult();
    }

    // fonction pour afficher les albums dont la date n'est pas passée, classé par date
    public function prochaineSortieAlbum()
    {

        return $this->createQueryBuilder('a')
            ->where('a.DateSorties > CURRENT_DATE()')
            ->orderBy('a.DateSorties')
            ->getQuery()
            ->getResult();
    }

    public function pagination()
    {
        return $this->createQueryBuilder('a')
            ->select('a.id', 'a.titre', 'a.DateSorties', 'a.producteur', 'a.filename', 'a.update_at', 'a.deezer_id')
            ->distinct(true)
            ->where('a.DateSorties > CURRENT_DATE()')
            ->orderBy('a.titre','ASC')
            ->getQuery()
            ->getResult();
    }
    private function findVisibleQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('a');
    }
    /**
     * Fonction pour la barre de recherche et l'affichage des albums classés par titre
     * @return Query
     */
    public function findAllVisibleQuery(AlbumsSearch $search): Query
    {
        $query =  $this->findVisibleQuery();
        if ($search->getTitre()) {
            $query = $query
                ->andwhere('a.titre LIKE :Titre')
                ->setParameter('Titre', '%' . $search->getTitre() . '%');
        }


        $query = $query
            ->orderBy('a.titre');
        return   $query->getQuery();
    }

    public function RechercheAlbum($term)
    {
        $qb = $this->createQueryBuilder('a');

        $qb->select('a.titre', 'a.id')
            ->where('a.titre LIKE :Titre')
            ->setParameter('Titre', $term . '%')
            ->innerJoin('a.artiste', 'ar')
            ->where('ar.id = :id')
            ->setParameter(':id', 5)
            ->setMaxResults(5);
            

        $arrayAss = $qb->getQuery()
            ->getArrayResult();

        // Transformer le tableau associatif en un tableau standard
        $array = array();
        foreach ($arrayAss as $data) {
            $array[] = [$data['titre'], $data['id']];
        }
    }    
}
