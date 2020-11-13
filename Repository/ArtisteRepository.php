<?php

namespace App\Repository;

use App\Entity\Artiste;
use Doctrine\ORM\Query;
use App\Entity\ArtisteSearch;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Artiste|null find($id, $lockMode = null, $lockVersion = null)
 * @method Artiste|null findOneBy(array $criteria, array $orderBy = null)
 * @method Artiste[]    findAll()
 * @method Artiste[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArtisteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Artiste::class);
    }

    private function findVisibleQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('ar');
    }

    public function nomArtiste($idArtist)
    {
        return $this->createQueryBuilder('ar')
            ->select('ar.nomArtiste')
            ->innerJoin('ar.albums', 'a')
            ->where('a.id = :id')
            ->setParameter(':id', $idArtist)
            ->getQuery()
            ->getResult();
    }

    public function nomArtisteSingle($idArtist)
    {
        return $this->createQueryBuilder('ar')
            ->select('ar.nomArtiste')
            ->innerJoin('ar.singles', 's')
            ->where('s.id = :id')
            ->setParameter(':id', $idArtist)
            ->getQuery()
            ->getResult();
    }

    public function nomArtisteTour($idArtist)
    {
        return $this->createQueryBuilder('ar')
            ->select('ar.nomArtiste')
            ->innerJoin('ar.tours', 't')
            ->where('t.id = :id')
            ->setParameter(':id', $idArtist)
            ->getQuery()
            ->getResult();
    }

    /**
     * Fonction pour la barre de recherche et l'affichage des artistes classés par titre
     * @return Query
     */
    public function findAllVisibleQuery(ArtisteSearch $search): Query
    {
        $query =  $this->findVisibleQuery();
        if ($search->getNom()) {
            $query = $query
                ->andwhere('ar.nomArtiste LIKE :Nom')
                ->setParameter('Nom', '%' . $search->getNom() . '%');
        }


        $query = $query
            ->orderBy('ar.id');
        return   $query->getQuery();
    }

    public function AutocompleteArtiste($term)
    {
        $qb = $this->createQueryBuilder('ar');

        $qb->select('ar.nomArtiste', 'ar.id')
            ->where('ar.nomArtiste LIKE :Artiste')
            ->setParameter('Artiste', $term . '%')
            ->setMaxResults(5);

        $arrayAss = $qb->getQuery()
            ->getArrayResult();

        // Transformer le tableau associatif en un tableau standard
        $array = array();
        foreach ($arrayAss as $data) {
            $array[] = [$data['nomArtiste'], $data['id']];
        }

        return $array;
    }

}
