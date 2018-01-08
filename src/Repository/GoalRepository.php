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

    public function findGoals()
    {
        return $this->getEntityManager()->createQuery(
            'SELECT g.name, g.status, c.name as category
            FROM App\Entity\Goal g
            JOIN g.category c
            WITH g.category = c.id            
            ORDER BY g.name ASC'
        )
        ->getResult();
    }

    public function findGoal($id)
    {
        return $this->getEntityManager()->createQuery(
            'SELECT g.name, g.status, c.name as category
            FROM App\Entity\Goal g
            JOIN g.category c
            WITH g.category = c.id            
            WHERE g.id = :id
            ORDER BY g.name ASC'
        )
        ->setParameter('id', $id)
        ->getResult();
    }   
}
