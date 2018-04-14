<?php

namespace App\Repository;

use App\Entity\Stage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class StageRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Stage::class);
    }

    public function getByIdAndUserId($id, $userId) : Stage
    {
        $found = $this->findOneBy([
            'id' => (int)$id,
            'userId' => (int)$userId
        ]);

        if (!$found) {
            throw new NotFoundHttpException(sprintf(
              'Dla zalogowanego użytkowniia nie znaleziono poziomu o id "%s"',
              $id
          ));
        }

        return $found;
    }
}
