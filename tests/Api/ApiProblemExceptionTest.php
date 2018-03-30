<?php

namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;
use App\Api\ApiProblemException;
use App\Api\ApiProblem;

class ApiProblemExceptionTest extends TestCase
{
    public function testApiProblemException()
    {
        $apiProblemException = new ApiProblemException(new ApiProblem(400));

        $this->assertEquals(400, $apiProblemException->getStatusCode());
        $this->assertEquals('Bad Request', $apiProblemException->getMessage());
    }
}
