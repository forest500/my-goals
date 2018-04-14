<?php

namespace App\Repository;

use App\Entity\Goal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GoalRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Goal::class);
    }

    public function getByIdAndUserId($id, $userId) : Goal
    {
        $found = $this->findOneBy([
            'id' => (int)$id,
            'userId' => (int)$userId
        ]);

        if (!$found) {
            throw new NotFoundHttpException(sprintf(
              'Dla zalogowanego użytkowniia nie znaleziono celu o id "%s"',
              $id
          ));
        }

        return $found;
    }
}
