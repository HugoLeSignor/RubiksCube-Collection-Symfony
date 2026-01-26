<?php

namespace App\Repository;

use App\Entity\UserCollection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserCollectionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserCollection::class);
    }

    public function findByUser(int $userId): array
    {
        return $this->createQueryBuilder('uc')
            ->join('uc.rubiksCube', 'r')
            ->andWhere('uc.user = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('uc.addedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function userHasCube(int $userId, int $cubeId): bool
    {
        $result = $this->createQueryBuilder('uc')
            ->select('COUNT(uc.id)')
            ->andWhere('uc.user = :userId')
            ->andWhere('uc.rubiksCube = :cubeId')
            ->setParameter('userId', $userId)
            ->setParameter('cubeId', $cubeId)
            ->getQuery()
            ->getSingleScalarResult();

        return $result > 0;
    }
}
