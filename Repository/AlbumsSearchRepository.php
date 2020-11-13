<?php

namespace App\Repository;

use App\Entity\AlbumsSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method AlbumsSearch|null find($id, $lockMode = null, $lockVersion = null)
 * @method AlbumsSearch|null findOneBy(array $criteria, array $orderBy = null)
 * @method AlbumsSearch[]    findAll()
 * @method AlbumsSearch[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AlbumsSearchRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AlbumsSearch::class);
    }

    
}
