<?php

namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;
use App\Api\ApiProblemException;
use App\Api\DeleteProcessor;

class DeleteProcessorTest extends TestCase
{
    public function testThrowForeignKeyException()
    {
        $this->expectException(ApiProblemException::class);

        $deleteProcessor = new DeleteProcessor();
        $deleteProcessor->throwForeignKeyException();
    }
}
