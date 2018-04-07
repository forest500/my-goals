<?php

namespace App\Api;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Api\ApiProblem;
use App\Api\ApiProblemException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;

class FormProcessor
{
    private $em;

    public function __construct(EntityManagerInterface $entityManager = null)
    {
        $this->em = $entityManager;
    }

    public function processForm(FormInterface $form, Request $request, int $userId = 0)
    {
        $data = json_decode($request->getContent(), true);
        $this->checkJson($data);

        if($userId !== 0) {
          $name = $data['name'];
          $class = $form->getConfig()->getDataClass();
          $this->checkUniqueName($class, $name, $userId);
        }

        $clearMissing = $request->getMethod() != 'PATCH';
        $form->submit($data, $clearMissing);
    }

    public function checkJson($data)
    {
        if ($data === null) {
            $apiProblem = new ApiProblem(400, ApiProblem::TYPE_INVALID_REQUEST_BODY_FORMAT);

            throw new ApiProblemException($apiProblem);
        }

        return true;
    }

    public function checkUniqueName($class, string $name, int $userId = 0)
    {
        $object = $this->em->getRepository($class)->findOneBy([
            'name' => $name,
            'userId' => $userId,
        ]);

        if (!empty($object)) {
            $apiProblem = new ApiProblem(400, ApiProblem::TYPE_UNIQUE_NAME_ERROR);
            $apiProblem->set('message', "$name już istnieje. Wybierz inną nazwe");

            throw new ApiProblemException($apiProblem);
        }

        return true;
    }
}
