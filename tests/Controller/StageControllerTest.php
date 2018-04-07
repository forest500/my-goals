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

        $response = $client->getResponse();
        $responseData = json_decode($response->getContent(), true);

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
        $this->assertTrue($response->headers->has('Location'));
        $this->assertEquals("/api/get_stage/".$responseData['id'], $response->headers->get('Location'));
        $this->assertEquals("poziom", $responseData['name']);
        $this->assertEquals("nagroda", $responseData['award']);
    }

    public function testGetAll()
    {
        $client = $this->createAuthenticatedClient();

        $this->createStage('poziom pierwszy', 'nagroda', '2018-01-01');
        $this->createStage('poziom drugi', 'milion', '1991-26-01');
        $this->createStage('poziom trzeci', 'bmw', '2018-01-01');

        $client->request('Get', 'api/get_stages');
        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertArrayHasKey('stages', $data);
        $this->assertEquals('milion', $data['stages'][1]['award']);
        $this->assertEquals('poziom trzeci', $data['stages'][2]['name']);
        $this->assertCount(3, $data['stages']);
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

    public function testGetByGoal()
    {
        $client = $this->createAuthenticatedClient();

        $this->createStage('poziom pierwszy', 'nagroda', '2018-01-01');
        $this->createStage('poziom drugi', 'milion', '1991-26-01');
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

        $data = '{"name":"zmieniony poziom", "award":"bmw","endDate":"2005-03-03"}';
        $client->request(
          'Put',
          "api/update_stage/$stageId",
          array(),
          array(),
          array(),
          $data
      );

      $response = $client->getResponse();
      $responseData = json_decode($response->getContent(), true);

      $this->assertEquals(200, $response->getStatusCode());
      $this->assertEquals('zmieniony poziom', $responseData['name']);
    }

    public function testDelete()
    {
        $client = $this->createAuthenticatedClient();

        $this->createStage('poziom pierwszy', 'nagroda', '2018-01-01');
        $stageId = $this->getObjId('poziom pierwszy', Stage::class);

        $client->request('Delete', "api/delete_stage/$stageId");

        $this->assertEquals(204, $client->getResponse()->getStatusCode());
    }

    protected function tearDown()
    {
        parent::tearDown();
    }
}
