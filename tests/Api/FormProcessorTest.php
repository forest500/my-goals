<?php

namespace App\Tests\Entity;

use Symfony\Component\Form\Test\TypeTestCase;
use App\Api\FormProcessor;
use Symfony\Component\HttpFoundation\Request;
use App\Api\ApiProblemException;

class FormProcessorTest extends TypeTestCase
{
    public function testThrowInvalidBodyException()
    {
        $this->expectException(ApiProblemException::class);
        $invalidBody =
          '{
              "name: "Miro",
              "number" : "2
          }';
        $data = json_decode($invalidBody, true);

        $formProcessor = new FormProcessor();
        $formProcessor->checkJson($data);
    }

    public function testValiddJsonBodySend()
    {
        $validBody =
        '{
            "name": "Miro",
            "number" : "2"
          }';

        $formProcessor = new FormProcessor();
        $this->assertTrue($formProcessor->checkJson($validBody));
    }

    public function testThrowUniqueNameException()
    {
        $this->expectException(ApiProblemException::class);


        $formProcessor = new FormProcessor();
        $formProcessor->checkJson($data);
    }
}
