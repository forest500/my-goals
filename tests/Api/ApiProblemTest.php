<?php

namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;
use App\Api\ApiProblem;
use Symfony\Component\HttpFoundation\Response;

class ApiProblemTest extends TestCase
{
    public function testToArray()
    {
        $apiProblem = new ApiProblem(400);
        $apiProblem->set('data', 'extra');
        $expectedArray = [
            'data' => 'extra',
            'status' => 400,
            'type' => 'about:blank',
            'title' => Response::$statusTexts[400],
        ];


        $this->assertEquals($expectedArray, $apiProblem->toArray());
        $this->assertEquals(400, $apiProblem->getStatusCode());
        $this->assertEquals(Response::$statusTexts[400], $apiProblem->getTitle());
    }
}
