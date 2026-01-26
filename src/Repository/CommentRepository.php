<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function findByRubiksCube(int $cubeId): array
    {
        return $this->createQueryBuilder('c')
            ->join('c.user', 'u')
            ->andWhere('c.rubiksCube = :cubeId')
            ->setParameter('cubeId', $cubeId)
            ->orderBy('c.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
