<?php

namespace App\Tests\Controller;

use \App\Entity\Goal;
use \App\Entity\Category;
use \App\Tests\ApiTestCase;

class GoalControllerTest extends ApiTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testPost()
    {
        $client = $this->createAuthenticatedClient();
        $data = '{"name":"cel"}';
        $categoryId = $this->getObjId('kategoria', Category::class);
        $client->request('Post', 'api/categories/'.$categoryId.'/goals', array(), array(), array(), $data);

        $response = $client->getResponse();
        $responseData = json_decode($response->getContent(), true);

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
        $this->assertTrue($response->headers->has('Location'));
        $this->assertEquals("/api/goals/".$responseData['id'], $response->headers->get('Location'));
        $this->assertArrayHasKey('name', $responseData);
        $this->assertEquals("cel", $responseData['name']);
    }

    public function testGetAll()
    {
        $client = $this->createAuthenticatedClient();
        $this->createGoal('cel');
        $this->createGoal('drugi cel');

        $client->request('Get', 'api/goals');
        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertArrayHasKey('goals', $data);
        $this->assertArrayHasKey('name', $data['goals'][0]);
        $this->assertEquals('drugi cel', $data['goals'][1]['name']);
        $this->assertCount(2, $data['goals']);
    }

    public function testGetOne()
    {
        $client = $this->createAuthenticatedClient();
        $this->createGoal('cel');
        $goalId = $this->getObjId('cel', Goal::class);

        $client->request('Get', "api/goals/$goalId");
        $data = json_decode($client->getResponse()->getContent());

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('cel', $data->name);
    }

    public function testGetByCategory()
    {
        $client = $this->createAuthenticatedClient();
        $this->createGoal('cel');
        $categoryId = $this->getObjId('kategoria', Category::class);

        $client->request('Get', 'api/categories/'.$categoryId.'/goals');
        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertArrayHasKey('goals', $data);
        $this->assertEquals('cel', $data['goals'][0]['name']);
        $this->assertCount(1, $data);
    }

    public function testPut()
    {
        $client = $this->createAuthenticatedClient();

        $this->createGoal('editable goal');
        $goalId = $this->getObjId('editable goal', Goal::class);

        $data = '{"name":"edited goal"}';
        $client->request(
          'Put',
          "api/goals/$goalId",
        array(),
          array(),
          array(),
          $data
      );

      $response = $client->getResponse();
      $responseData = json_decode($response->getContent(), true);

      $this->assertEquals(200, $response->getStatusCode());
      $this->assertEquals('edited goal', $responseData['name']);
    }

    public function testDelete()
    {
        $client = $this->createAuthenticatedClient();

        $this->createGoal('goal to delete');
        $goalId = $this->getObjId('goal to delete', Goal::class);

        $client->request('Delete', "api/goals/$goalId");

        $this->assertEquals(204, $client->getResponse()->getStatusCode());
    }

    protected function tearDown()
    {
        parent::tearDown();
    }
}
