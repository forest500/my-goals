<?php

namespace App\Tests\Entity;

use \App\Tests\ApiTestCase;

class FormProcessorTest extends ApiTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testInvalidJson()
    {
        $invalidBody =
          '{
              "name: "Miro",
              "description" : "dsfsdf
          }';
        $client = $this->createAuthenticatedClient();

        $client->request('Post', 'api/categories', array(), array(), array(), $invalidBody);
        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertArrayHasKey('type', $data);
        $this->assertEquals($data['title'], 'Invalid JSON format sent');
    }
}
