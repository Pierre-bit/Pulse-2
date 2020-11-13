<?php

namespace App\Repository;

use App\Entity\TourSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TourSearch|null find($id, $lockMode = null, $lockVersion = null)
 * @method TourSearch|null findOneBy(array $criteria, array $orderBy = null)
 * @method TourSearch[]    findAll()
 * @method TourSearch[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TourSearchRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TourSearch::class);
    }
}
