<?php

namespace App\Tests\Entity;

use \App\Entity\Goal;
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
        $categoryId = $this->getCategoryId();
        $client->request('Post', 'api/new_goal/'.$categoryId, array(), array(),array(),$data);

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
        $this->assertEquals("Dodano kategorię!", json_decode($client->getResponse()->getContent()));
    }

    public function testGetAll()
    {
        $client = $this->createAuthenticatedClient();
        $this->createCategory();

        $client->request('Get', 'api/get_categories');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $data = json_decode($client->getResponse()->getContent());
        $this->assertEquals('kategoria', $data[0]->name);
        $this->assertEquals('opis kategorii', $data[0]->description);
    }

    public function testPut()
    {
      $client = $this->createAuthenticatedClient();

      $categoryId = $this->getCategoryId();

      $data = '{"name":"edytowana kategoria", "description":"nowy opis kategorii"}';
      $client->request('Put', "api/update_category/$categoryId",
        array(), array(), array(),$data);

      $this->assertEquals(200, $client->getResponse()->getStatusCode());
      $this->assertEquals("Kategoria została zmieniona", json_decode($client->getResponse()->getContent()));
    }

    public function testDelete()
    {
      $client = $this->createAuthenticatedClient();

      $category = $this->getEntityManager()
          ->getRepository(Category::class)
          ->findOneBy(['name' => 'kategoria']);
      $categoryId = $category->getId();

      $data = '{"name":"edytowana kategoria", "description":"nowy opis kategorii"}';
      $client->request('Delete',"api/delete_category/$categoryId");

      $this->assertEquals(200, $client->getResponse()->getStatusCode());
      $this->assertEquals("Kategoria została usunieta", json_decode($client->getResponse()->getContent()));
    }


}
