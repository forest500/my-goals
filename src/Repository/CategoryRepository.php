<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function findCategories($userId)
    {
        return $this->getEntityManager()->createQuery(
            'SELECT c.id, c.name, c.description
            FROM App\Entity\Category c
            WHERE c.userId = :userId
            ORDER BY c.id ASC'
        )
        ->setParameter('userId', $userId)
        ->getResult();
    }

    public function findCategory($id)
    {
        return $this->getEntityManager()->createQuery(
            'SELECT c.name, c.description
            FROM App\Entity\Category c
            WHERE c.id = :id'
        )
        ->setParameter('id', $id)
        ->getResult();
    }
}
