<?php

namespace App\Service;

use App\Entity\Category;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class ObjectGenerator
{
  private $entityManager;

  public function __construct(EntityManagerInterface $entityManager)
  {
    $this->entityManager = $entityManager;
  }

  public function generateCategory(User $userId)
  {
    $categoriesData = [
      ['name' => 'Zdrowie', 'description' => 'Zdrowie fizyczne i mentalne, styl życia, sport', 'userId' => $userId],
      ['name' =>' Kariera', 'description' => 'Rozwój kariery, praca, biznes', 'userId' => $userId],
      ['name' => 'Relacje', 'description' => 'Relacje z rodziną przyjaciółmi, a także z drugą połówką', 'userId' => $userId],
      ['name' => 'Osobiste', 'description' => 'Rozwój osobisty, nauka oraz projekty', 'userId' => $userId],
      ['name' => 'Podróże', 'description' => 'Cele podróży, miejsca, które trzeba odwiedzić', 'userId' => $userId],
      ['name' => 'Finanse', 'description' => 'Zamierzony przychód, spłata zobowiązań', 'userId' => $userId],
      ['name' => 'Coś dla świata', 'description' => 'Wkład w cele społeczne, pomoc innym', 'userId' => $userId]
    ];

    foreach($categoriesData as $categoryData) {
      $category = new Category();
      $category->setName($categoryData['name']);
      $category->setDescription($categoryData['description']);
      $category->setUserId($categoryData['userId']);

      $this->entityManager->persist($category);
      $this->entityManager->flush();
    }

  }

}
