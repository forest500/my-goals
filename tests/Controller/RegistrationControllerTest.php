<?php

namespace App\Tests\Entity;

use \App\Tests\ApiTestCase;
use App\Service\ObjectGenerator;

class RegistrationControllerTest extends ApiTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testRegistration()
    {
      $client = $this->createAuthenticatedClient();
      $data = '{
                  "email": "lasek@gmail.com",
                  "plainPassword":  {
                      "first": "password",
                      "second":"password"
                   },
                   "termsAccepted": true
                }';

      $client->request('Post', 'api/register', array(), array(), array(), $data);

      $this->assertEquals(201, $client->getResponse()->getStatusCode());
      $this->assertEquals("Dodano nowego użytkownika", json_decode($client->getResponse()->getContent()));
    }



    protected function tearDown()
    {
        parent::tearDown();
    }
}
