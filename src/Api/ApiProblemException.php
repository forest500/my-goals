<?php

namespace App\Api;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ApiProblemException extends HttpException
{
  private $apiProblem;

  public function __construct(ApiProblem $apiProblem, $statuscode, $message)
  {
    $this->apiProblem = $apiProblem;


  }
}
