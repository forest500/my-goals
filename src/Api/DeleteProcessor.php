<?php

namespace App\Api;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Api\ApiProblem;
use App\Api\ApiProblemException;
use Symfony\Component\HttpFoundation\JsonResponse;

class DeleteProcessor
{
    public function throwForeignKeyException()
    {
      $apiProblem = new ApiProblem(400, ApiProblem::TYPE_FOREIGN_KEY_CONSTRAINT_VIOLATION);
      $apiProblem->set('error', 'Aby usunąc wybraną kategorie nalezy najpierw usunac cele, ktore sie w niej znajduja');

      throw new ApiProblemException($apiProblem);
    }
}
