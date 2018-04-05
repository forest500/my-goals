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

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function processForm(FormInterface $form, Request $request)
    {
        $data = json_decode($request->getContent(), true);
        if ($data === null) {
            $apiProblem = new ApiProblem(400, ApiProblem::TYPE_INVALID_REQUEST_BODY_FORMAT);

            throw new ApiProblemException($apiProblem);
        }
        $this->checkUniqueName($data['name'], $form);

        $clearMissing = $request->getMethod() != 'PATCH';
        $form->submit($data, $clearMissing);
    }

    public function checkUniqueName(string $name, FormInterface $form)
    {
        $class = $form->getConfig()->getDataClass();
        $object = $this->em->getRepository($class)->findByName($name);
        if (!empty($object)) {
            $apiProblem = new ApiProblem(400, ApiProblem::TYPE_UNIQUE_NAME_ERROR);
            $apiProblem->set('message', "$name już istnieje. Wybierz inną nazwe");

            throw new ApiProblemException($apiProblem);
        }
    }
}
