<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    public function testHome()
    {
        $client = static::createClient();
        $client->request('Get', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
