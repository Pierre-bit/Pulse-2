<?php

namespace App\Repository;

use App\Entity\Single;
use Doctrine\ORM\Query;
use App\Entity\SingleSearch;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Single|null find($id, $lockMode = null, $lockVersion = null)
 * @method Single|null findOneBy(array $criteria, array $orderBy = null)
 * @method Single[]    findAll()
 * @method Single[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SingleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Single::class);
    }

    public function selectSingle($idArtist)
    {
        return $this->createQueryBuilder('s')
            ->select('s.id', 's.titre', 's.DateSorties','s.filename', 's.update_at', 's.deezer_id')
            ->distinct(true)
            ->innerJoin('s.artistes', 'ar')
            ->where('ar.id = :id')
            ->setParameter(':id', $idArtist)
            ->orderBy('s.DateSorties', 'DESC')
            ->getQuery()
            ->getResult();
    }
    public function newsSingleIndex()
    {
        return $this->createQueryBuilder('s')
            ->select('s')
            ->distinct(true)
            ->leftJoin('s.news', 'n')
            ->andWhere('n.single = s.id')

            ->getQuery()
            ->getResult();
    }

    // fonction pour afficher les albums dont la date n'est pas passée, classé par date
    public function prochaineSortieSingle()
    {

        return $this->createQueryBuilder('s')
            ->where('s.DateSorties > CURRENT_DATE()')
            ->orderBy('s.DateSorties')
            ->getQuery()
            ->getResult();
    }

    public function pagination()
    {
        return $this->createQueryBuilder('a')
            ->select('s.id', 's.titre', 's.DateSorties','s.filename', 's.update_at', 's.deezer_id')
            ->distinct(true)
            ->where('a.DateSorties > CURRENT_DATE()')
            ->orderBy('a.titre', 'ASC')
            ->getQuery()
            ->getResult();
    }
    private function findVisibleQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('s');
    }
    /**
     * Fonction pour la barre de recherche et l'affichage des singles classés par titre
     * @return Query
     */
    public function findAllVisibleQuery(SingleSearch $search): Query
    {
        $query =  $this->findVisibleQuery();
        if ($search->getTitre()) {
            $query = $query
                ->innerJoin('s.artistes', 'ar')
                ->where('ar.id = s.artistes')
                ->andwhere('s.titre LIKE :Titre')
                ->setParameter('Titre', '%' . $search->getTitre() . '%');
        }


        $query = $query
            ->orderBy('s.id');
        return   $query->getQuery();
    }
}
