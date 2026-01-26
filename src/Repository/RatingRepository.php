<?php

namespace App\Repository;

use App\Entity\Rating;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class RatingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rating::class);
    }

    public function findUserRatingForCube(int $userId, int $cubeId): ?Rating
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.user = :userId')
            ->andWhere('r.rubiksCube = :cubeId')
            ->setParameter('userId', $userId)
            ->setParameter('cubeId', $cubeId)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
