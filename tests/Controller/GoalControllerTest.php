<?php

namespace App\Tests\Entity;

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
        $client->request('Post', 'api/new_goal/'.$categoryId, array(), array(), array(), $data);

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
        $this->assertEquals("Dodano cel!", json_decode($client->getResponse()->getContent()));
    }

    public function testGetAll()
    {
        $client = $this->createAuthenticatedClient();
        $this->createGoal('cel');
        $this->createGoal('drugi cel');

        $client->request('Get', 'api/get_goals');
        $data = json_decode($client->getResponse()->getContent());
        $data = $data->goals;

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertObjectHasAttribute('name', $data[0]);
        $this->assertEquals('drugi cel', $data[1]->name);
        $this->assertCount(2, $data);
    }

    public function testGetOne()
    {
        $client = $this->createAuthenticatedClient();
        $this->createGoal('cel');
        $goalId = $this->getObjId('cel', Goal::class);

        $client->request('Get', "api/get_goal/$goalId");
        $data = json_decode($client->getResponse()->getContent());

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('cel', $data[0]->name);
        $this->assertCount(1, $data);
    }

    public function testGetByCategory()
    {
        $client = $this->createAuthenticatedClient();
        $this->createGoal('cel');
        $categoryId = $this->getObjId('kategoria', Category::class);

        $client->request('Get', "api/get_category_goals/$categoryId");
        $data = json_decode($client->getResponse()->getContent());
        $data = $data->goals;        

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('cel', $data[0]->name);
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
          "api/update_goal/$goalId",
        array(),
          array(),
          array(),
          $data
      );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals("Zmieniono cel!", json_decode($client->getResponse()->getContent()));
    }

    public function testDelete()
    {
        $client = $this->createAuthenticatedClient();

        $this->createGoal('goal to delete');
        $goalId = $this->getObjId('goal to delete', Goal::class);

        $client->request('Delete', "api/delete_goal/$goalId");

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals("Cel został usunięty", json_decode($client->getResponse()->getContent()));
    }

    protected function tearDown()
    {
        parent::tearDown();
    }
}
