<?php

namespace App\Tests\Entity;

use \App\Entity\Goal;
use \App\Entity\Stage;
use \App\Entity\Category;
use \App\Tests\ApiTestCase;

class StageControllerTest extends ApiTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testPost()
    {
        $client = $this->createAuthenticatedClient();

        $data = '{"name":"poziom", "award": "nagroda", "endDate": "2018-01-01"}';

        $this->createGoal('cel');
        $goalId = $this->getObjId('cel', Goal::class);

        $client->request('Post', "api/new_stage/$goalId", array(), array(),array(),$data);

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
        $this->assertEquals("Dodano poziom!", json_decode($client->getResponse()->getContent()));
    }

    public function testGetAll()
    {
        $client = $this->createAuthenticatedClient();

        $this->createStage('poziom pierwszy', 'nagroda', '2018-01-01');
        $this->createStage('poziom drugi', 'milion', '1991-26-01');
        $this->createStage('poziom trzeci', 'bmw', '2018-01-01');

        $client->request('Get', 'api/get_stages');
        $data = json_decode($client->getResponse()->getContent());
        $data = $data->stages;

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertObjectHasAttribute('name', $data[0]);
        $this->assertObjectHasAttribute('award', $data[0]);
        $this->assertObjectHasAttribute('endDate', $data[0]);
        $this->assertEquals('bmw', $data[2]->award);
        $this->assertCount(3, $data);
    }

    public function testGetOne()
    {
        $client = $this->createAuthenticatedClient();

        $this->createStage('poziom pierwszy', 'nagroda', '2018-01-01');
        $stageId = $this->getObjId('poziom pierwszy', Stage::class);

        $client->request('Get', "api/get_stage/$stageId");
        $data = json_decode($client->getResponse()->getContent());

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('poziom pierwszy', $data->name);
    }

    public function testGetByCategory()
    {
        $client = $this->createAuthenticatedClient();

        $this->createStage('poziom pierwszy', 'nagroda', '2018-01-01');
        $this->createStage('poziom drugi', 'milion', '1991-26-01');

        $categoryId = $this->getObjId('kategoria', Category::class);

        $client->request('Get', "api/get_category_stages/$categoryId");
        $data = json_decode($client->getResponse()->getContent());
        $data = $data->stages;

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('nagroda', $data[0]->award);
        $this->assertCount(2, $data);
    }

    public function testGetByGoal()
    {
        $client = $this->createAuthenticatedClient();

        $this->createStage('poziom pierwszy', 'nagroda', '2018-01-01');
        $this->createStage('poziom drugi', 'milion', '1991-26-01');

        $this->createGoal('cel');
        $goalId = $this->getObjId('cel', Goal::class);

        $client->request('Get', "api/get_goal_stages/$goalId");
        $data = json_decode($client->getResponse()->getContent());
        $data = $data->stages;

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('poziom pierwszy', $data[0]->name);
        $this->assertCount(2, $data);
    }


    public function testPut()
    {
        $client = $this->createAuthenticatedClient();

        $this->createStage('poziom pierwszy', 'nagroda', '2018-01-01');
        $stageId = $this->getObjId('poziom pierwszy', Stage::class);

        $data = '{"name":"zmieniony poziom"}';
        $client->request(
          'Put',
          "api/update_stage/$stageId",
        array(),
          array(),
          array(),
          $data
      );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals("Zmodyfikowano poziom!", json_decode($client->getResponse()->getContent()));
    }

    public function testDelete()
    {
        $client = $this->createAuthenticatedClient();

        $this->createStage('poziom pierwszy', 'nagroda', '2018-01-01');
        $stageId = $this->getObjId('poziom pierwszy', Stage::class);

        $client->request('Delete', "api/delete_stage/$stageId");

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals("Poziom został usunięty", json_decode($client->getResponse()->getContent()));
    }

    protected function tearDown()
    {
        parent::tearDown();
    }
}
