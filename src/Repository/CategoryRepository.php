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

    public function findCategories()
    {
        return $this->getEntityManager()->createQuery(
            'SELECT c.name, c.description
            FROM App\Entity\Category c'
        )
        ->getResult();
    }

    public function findCategory($id)
    {
        return $this->getEntityManager()->createQuery(
            'SELECT c.name, c.description
            FROM App\Entity\Category c
            WHERE c.id = :id
            ORDER BY c.name ASC'
        )
        ->setParameter('id', $id)
        ->getResult();
    }    
}
