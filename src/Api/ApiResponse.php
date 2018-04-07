<?php

namespace App\Api;

use Symfony\Component\HttpFoundation\Response;
use App\Api\Serializer;

class ApiResponse
{
    public function createResponse($data, int $statusCode = 200)
    {
        $serializer = new Serializer();
        $serializedData = $serializer->serialize($data);

        return new Response($serializedData, $statusCode, array(
            'Content-Type' => 'application/json'
        ));
    }
}
