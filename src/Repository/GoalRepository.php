<?php

namespace App\Repository;

use App\Entity\Goal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class GoalRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Goal::class);
    }

    public function findGoals($userId)
    {
        return $this->getEntityManager()->createQuery(
            'SELECT g.id, g.name, g.status, c.name as category, c.id as categoryId
            FROM App\Entity\Goal g
            JOIN g.category c
            WITH g.category = c.id
            WHERE g.userId = :userId
            ORDER BY g.id ASC'
        )
        ->setParameter('userId', $userId)
        ->getResult();
    }

    public function findGoal($id)
    {
        return $this->getEntityManager()->createQuery(
            'SELECT g.name, g.status, c.name as category, c.id as categoryId
            FROM App\Entity\Goal g
            JOIN g.category c
            WITH g.category = c.id
            WHERE g.id = :id'
        )
        ->setParameter('id', $id)
        ->getOneOrNullResult();;
    }

    public function findByCategory($id)
    {
        return $this->getEntityManager()->createQuery(
            'SELECT g.id, g.name, g.status, c.name as category, c.id as categoryId
            FROM App\Entity\Goal g
            JOIN g.category c
            WITH g.category = c.id
            WHERE g.category = :id
            ORDER BY g.id ASC'
        )
        ->setParameter('id', $id)
        ->getResult();
    }
}
