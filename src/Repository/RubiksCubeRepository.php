<?php

namespace App\Repository;

use App\Entity\RubiksCube;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class RubiksCubeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RubiksCube::class);
    }

    public function findByType(string $type): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.type = :type')
            ->setParameter('type', $type)
            ->orderBy('r.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function searchByNameOrBrand(string $search): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.name LIKE :search OR r.brand LIKE :search')
            ->setParameter('search', '%' . $search . '%')
            ->orderBy('r.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findTopRated(int $limit = 10): array
    {
        return $this->createQueryBuilder('r')
            ->leftJoin('r.ratings', 'rating')
            ->groupBy('r.id')
            ->orderBy('AVG(rating.rating)', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
