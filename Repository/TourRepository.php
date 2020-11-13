<?php

namespace App\Repository;

use App\Entity\Tour;
use Doctrine\ORM\Query;
use App\Entity\TourSearch;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Tour|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tour|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tour[]    findAll()
 * @method Tour[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TourRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tour::class);
    }
    public function selectTour($idArtist)
    {
        return $this->createQueryBuilder('t')
            ->select('t.id', 't.nom', 't.Date', 't.filename', 't.update_at')
            ->orderBy('t.Date', 'DESC')
            ->distinct(true)
            ->innerJoin('t.artiste', 'ar')
            ->where('ar.id = :id')
            ->setParameter(':id', $idArtist)
            ->getQuery()
            ->getResult();
    }

    // fonction pour afficher les albums dont la date n'est pas passée, classé par date
    public function prochaineSortieTour()
    {

        return $this->createQueryBuilder('t')
            ->where('t.Date > CURRENT_DATE()')
            ->orderBy('t.Date')
            ->getQuery()
            ->getResult();
    }

    public function pagination()
    {
        return $this->createQueryBuilder('a')
            ->select('t.id', 't.nom', 't.Date', 't.filename', 't.update_at')
            ->distinct(true)
            ->where('a.DateSorties > CURRENT_DATE()')
            ->orderBy('a.titre', 'ASC')
            ->getQuery()
            ->getResult();
    }
    private function findVisibleQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('t');
    }
    /**
     * Fonction pour la barre de recherche et l'affichage des singles classés par titre
     * @return Query
     */
    public function findAllVisibleQuery(TourSearch $search): Query
    {
        $query =  $this->findVisibleQuery();
        if ($search->getnom()) {
            $query = $query
                ->andwhere('t.nom LIKE :Nom')
                ->setParameter('Nom', '%' . $search->getNom() . '%');
        }


        $query = $query
            ->orderBy('t.nom');
        return   $query->getQuery();
    }
}
