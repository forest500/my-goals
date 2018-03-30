<?php

namespace App\Tests\Entity;

use Symfony\Component\Form\Test\TypeTestCase;
use App\Api\FormProcessor;
use Symfony\Component\HttpFoundation\Request;
use App\Api\ApiProblemException;

class FormProcessorTest extends TypeTestCase
{
    public function testThrowException()
    {
        $this->expectException(ApiProblemException::class);
        $invalidBody =
          '{
              "name: "Miro",
              "number" : "2
          }';

        $request = new Request(array(), array(), array(), array(), array(), array(), $invalidBody);

        $form = $this->factory->create();


        $formProcessor = new FormProcessor();
        $formProcessor->processForm($form, $request);
    }

    public function testValidDataIsSubmitted()
    {
      $validBody =
        '{
            "name": "Miro",
            "number" : "2"
          }';

      $request = new Request(array(), array(), array(), array(), array(), array(), $validBody);
      $form = $this->factory->create();

      $formProcessor = new FormProcessor();
      $formProcessor->processForm($form, $request);
      
      $this->assertTrue($form->isSubmitted());
  }
}
