<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function getByIdAndUserId($id, $userId) : Category
    {
        $found = $this->findOneBy([
            'id' => (int)$id,
            'userId' => (int)$userId
        ]);

        if (!$found) {
            throw new NotFoundHttpException(sprintf(
              'Dla zalogowanego użytkowniia nie znaleziono kategorii o id "%s"',
              $id
          ));
        }

        return $found;
    }
}
