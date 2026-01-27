<?php

namespace App\Repository;

use App\Entity\Contact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Contact>
 */
class ContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contact::class);
    }

    /**
     * @return Contact[] Returns an array of Contact objects
     */
    public function findUnreadMessages(): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.isRead = :val')
            ->setParameter('val', false)
            ->orderBy('c.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Contact[] Returns an array of Contact objects
     */
    public function findUnrepliedMessages(): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.isReplied = :val')
            ->setParameter('val', false)
            ->orderBy('c.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
