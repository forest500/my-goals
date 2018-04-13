<?php

namespace App\Tests\Controller;

use \App\Entity\Category;
use \App\Tests\ApiTestCase;

class CategoryControllerTest extends ApiTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testPost()
    {
        $client = $this->createAuthenticatedClient();
        $data = '{"name":"nowa kategoria", "description":"opis kategorii"}';
        $client->request('Post', 'api/categories', array(), array(), array(), $data);

        $response = $client->getResponse();
        $responseData = json_decode($response->getContent(), true);

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
        $this->assertTrue($response->headers->has('Location'));
        $this->assertEquals("/api/categories/".$responseData['id'], $response->headers->get('Location'));
        $this->assertArrayHasKey('name', $responseData);

        $this->assertArrayHasKey('description', $responseData);
        $this->assertEquals("nowa kategoria", $responseData['name']);
        $this->assertEquals("opis kategorii", $responseData['description']);
    }

    public function testGetAll()
    {
        $client = $this->createAuthenticatedClient();
        $this->createCategory('druga kategoria', 'drugi opis');

        $client->request('Get', 'api/categories');
        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertArrayHasKey('categories', $data);
        $this->assertEquals('druga kategoria', $data['categories'][1]['name']);
        $this->assertEquals('opis kategorii', $data['categories'][0]['description']);
        $this->assertCount(2, $data['categories']);
    }

    public function testGetOne()
    {
        $client = $this->createAuthenticatedClient();
        $categoryId = $this->getObjId('kategoria', Category::class);

        $client->request('Get', "api/categories/$categoryId");

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());

        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(['id', 'name', 'description'], array_keys($data));
        $this->assertEquals('kategoria', $data['name']);
        $this->assertEquals('opis kategorii', $data['description']);
    }

    public function testPut()
    {
        $client = $this->createAuthenticatedClient();
        $categoryId = $this->getObjId('kategoria', Category::class);

        $data = '{"name":"edytowana kategoria", "description":"nowy opis kategorii"}';
        $client->request(
          'Put',
          "api/categories/$categoryId",
          array(),
          array(),
          array(),
          $data
        );
        $response = $client->getResponse();
        $responseData = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('edytowana kategoria', $responseData['name']);
        $this->assertEquals('nowy opis kategorii', $responseData['description']);
    }

    public function testDelete()
    {
        $client = $this->createAuthenticatedClient();
        $categoryId = $this->getObjId('kategoria', Category::class);

        $client->request('Delete', "api/categories/$categoryId");

        $this->assertEquals(204, $client->getResponse()->getStatusCode());
    }

    protected function tearDown()
    {
        parent::tearDown();
    }
}
