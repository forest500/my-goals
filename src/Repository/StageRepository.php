<?php

namespace App\Repository;

use App\Entity\Stage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class StageRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Stage::class);
    }

    public function findStages($userId)
    {
        return $this->getEntityManager()->createQuery(
            'SELECT s.id, g.name as goal, g.id as goalId, s.number, s.name, s.status, s.award, s.endDate
            FROM App\Entity\Stage s
            JOIN s.goal g
            WITH s.goal = g.id
            WHERE s.userId = :userId'
        )
        ->setParameter('userId', $userId)        
        ->getResult();
    }

    public function findStage($id)
    {
        return $this->getEntityManager()->createQuery(
            'SELECT g.name as goal, s.number, s.name, s.status, s.award, s.endDate
            FROM App\Entity\Stage s
            JOIN s.goal g
            WITH s.goal = g.id
            WHERE s.id = :id
            ORDER BY g.name ASC'
        )
        ->setParameter('id', $id)
        ->getResult();
    }

    public function findByGoal($id)
    {
        return $this->getEntityManager()->createQuery(
            'SELECT s.id, g.name as goal, g.id as goalId, s.number, s.name, s.status, s.award, s.endDate
            FROM App\Entity\Stage s
            JOIN s.goal g
            WITH s.goal = g.id
            WHERE s.goal = :id
            ORDER BY s.number ASC'
        )
        ->setParameter('id', $id)
        ->getResult();
    }

    public function findByCategory($id)
    {
        return $this->getEntityManager()->createQuery(
            'SELECT s.id, g.name as goal, g.id as goalId, s.number, s.name, s.status, s.award, s.endDate
            FROM App\Entity\Stage s
            JOIN s.goal g
            WITH s.goal = g.id
            JOIN g.category c
            WITH g.category = c.id
            WHERE g.category = :id
            ORDER BY s.number ASC'
        )
        ->setParameter('id', $id)
        ->getResult();
    }
}
