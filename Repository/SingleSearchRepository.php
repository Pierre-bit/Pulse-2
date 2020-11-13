<?php

namespace App\Repository;

use App\Entity\SingleSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method SingleSearch|null find($id, $lockMode = null, $lockVersion = null)
 * @method SingleSearch|null findOneBy(array $criteria, array $orderBy = null)
 * @method SingleSearch[]    findAll()
 * @method SingleSearch[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SingleSearchRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SingleSearch::class);
    }
}
