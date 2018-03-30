<?php

namespace App\Tests\Entity;

use Symfony\Component\Form\Test\TypeTestCase;
use App\Api\FormValidator;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormError;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class FormValidatorTest extends TypeTestCase
{
    public function testGetErrorsFromForm()
    {
        $form = $this->factory->create();
        $form->addError(new FormError('first error'));
        $form->addError(new FormError('second error'));

        $errors = ['first error', 'second error'];

        $formValidator = new FormValidator();

        $this->assertEquals($errors, $formValidator->getErrorsFromForm($form));
    }

    public function testCreateValidationErrorResponse()
    {
      $form = $this->factory->create();
      $form->addError(new FormError('error'));

      $formValidator = new FormValidator();
      $jsonResponse = $formValidator->createValidationErrorResponse($form);
      $responseContent = '{"errors":["error"],"status":400,"type":"validation_error","title":"There was a validation error"}';

      $this->assertEquals(400, $jsonResponse->getStatusCode());
      $this->assertEquals($responseContent, $jsonResponse->getContent());
      $this->assertTrue($jsonResponse->headers->contains('Content-Type', 'application/problem+json'));
    }
}
