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

    // public function setDefaultCategory($userId)
    // {
    //     return $this->getEntityManager()->createQuery(
    //         'INSERT INTO `category` (`id`, `name`, `description`, `userId`) VALUES
    //         (154, 'Zdrowie', 'Zdrowie fizyczne i mentalne, styl życia, sport', :userId),
    //         (155, 'Kariera', 'Rozwój kariery, praca, biznes', :userId),
    //         (156, 'Relacje', 'Relacje z rodziną przyjaciółmi, a także z drugą połówką', :userId),
    //         (157, 'Osobiste', 'Rozwój osobisty, nauka oraz projekty', 1),
    //         (158, 'Podróże', 'Cele podróży, miejsca, które trzeba odwiedzić', :userId),
    //         (159, 'Finanse', 'Zamierzony przychód, spłata zobowiązań', :userId),
    //         (160, 'Coś dla świata', 'Wkład w cele społeczne, pomoc innym', :userId)'
    //     )
    //     ->setParameter('userId', $userId)
    //     ->getResult();
    // }
}
