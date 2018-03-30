<?php

namespace App\Tests\Entity;

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

        $data = '{"name":"kategoria", "description":"opis kategorii"}';

        $client->request('Post', 'api/new_category', array(), array(), array(), $data);

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
        $this->assertEquals("Dodano kategorię!", json_decode($client->getResponse()->getContent()));
    }

    public function testGetAll()
    {
        $client = $this->createAuthenticatedClient();
        $this->createCategory('druga kategoria', 'drugi opis');

        $client->request('Get', 'api/get_categories');
        $data = json_decode($client->getResponse()->getContent());

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertObjectHasAttribute('name', $data[0]);
        $this->assertObjectHasAttribute('description', $data[0]);
        $this->assertEquals('druga kategoria', $data[1]->name);
        $this->assertEquals('drugi opis', $data[1]->description);
        $this->assertCount(2, $data);
    }

    public function testGetOne()
    {
        $client = $this->createAuthenticatedClient();
        $categoryId = $this->getObjId('kategoria', Category::class);

        $client->request('Get', "api/get_category/$categoryId");
        $data = json_decode($client->getResponse()->getContent());

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('kategoria', $data[0]->name);
        $this->assertEquals('opis kategorii', $data[0]->description);
        $this->assertCount(1, $data);
    }

    public function testPut()
    {
        $client = $this->createAuthenticatedClient();
        $categoryId = $this->getObjId('kategoria', Category::class);

        $data = '{"name":"edytowana kategoria", "description":"nowy opis kategorii"}';
        $client->request(
          'Put',
          "api/update_category/$categoryId",
        array(),
          array(),
          array(),
          $data
      );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals("Kategoria została zmieniona", json_decode($client->getResponse()->getContent()));
    }

    public function testDelete()
    {
        $client = $this->createAuthenticatedClient();
        $categoryId = $this->getObjId('kategoria', Category::class);

        $client->request('Delete', "api/delete_category/$categoryId");

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals("Kategoria została usunieta", json_decode($client->getResponse()->getContent()));
    }

    protected function tearDown()
    {
        parent::tearDown();
    }
}
