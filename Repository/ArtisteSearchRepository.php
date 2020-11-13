<?php

namespace App\Repository;

use App\Entity\ArtisteSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ArtisteSearch|null find($id, $lockMode = null, $lockVersion = null)
 * @method ArtisteSearch|null findOneBy(array $criteria, array $orderBy = null)
 * @method ArtisteSearch[]    findAll()
 * @method ArtisteSearch[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArtisteSearchRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ArtisteSearch::class);
    }

}
