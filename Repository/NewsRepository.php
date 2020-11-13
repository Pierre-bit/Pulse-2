<?php

namespace App\Repository;

use App\Entity\News;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method News|null find($id, $lockMode = null, $lockVersion = null)
 * @method News|null findOneBy(array $criteria, array $orderBy = null)
 * @method News[]    findAll()
 * @method News[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NewsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, News::class);
    }

    public function newsSingle()
    {
        return $this->createQueryBuilder('n')
            ->select('n.id', 'n.titre', 'n.description')
            ->distinct(true)
            ->leftJoin('n.single', 's')
            ->where('s.id = n.single')
            ->getQuery()
            ->getResult();
    }

    public function news()
    {
        return $this->createQueryBuilder('n')
            ->select('n')
            ->distinct(true)
            ->leftJoin('n.artiste', 'ar')
            ->andWhere('ar.id = n.artiste')
            ->addSelect('ar')
            ->getQuery()
            ->getResult();
    }

    public function artisteNews($idNews)
    {
        return $this->createQueryBuilder('n')
            ->select('n')
            ->distinct(true)
            ->innerJoin('n.artiste', 'ar')
            ->where('n.id = :id')
            ->setParameter(':id', $idNews)

            ->getQuery()
            ->getResult();
    }
}
