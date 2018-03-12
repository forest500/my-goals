<?php

namespace App\Tests\Entity;

use \App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CategoryControllerTest extends WebTestCase
{
    private $objectManager;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    protected function setUp()
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testPost()
    {
        $client = static::createClient();
        $data = '{"name":"kategoria", "description":"opis kategorii"}';
        $client->request(
          'Post',
          '/new_category',
          array(),
          array(),
          array(
            'CONTENT_TYPE' => 'application/json',
            'PHP_AUTH_USER' => 'lasekdeveloper@gmail.com',
            'PHP_AUTH_PW' => 'password'),
          $data
          );

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
        $this->assertEquals("Dodano kategorię!", json_decode($client->getResponse()->getContent()));
    }

    public function testPut()
    {
      $client = static::createClient();

      $category = $this->entityManager
          ->getRepository(Category::class)
          ->findOneBy(['name' => 'kategoria']);
      $categoryId = $category->getId();

      $data = '{"name":"edytowana kategoria", "description":"nowy opis kategorii"}';
      $client->request(
        'Put',
        "/update_category/$categoryId",
        array(),
        array(),
        array(
          'CONTENT_TYPE' => 'application/json',
          'PHP_AUTH_USER' => 'lasekdeveloper@gmail.com',
          'PHP_AUTH_PW' => 'password'),
        $data
        );

      $this->assertEquals(200, $client->getResponse()->getStatusCode());
      $this->assertEquals("Kategoria została zmieniona", json_decode($client->getResponse()->getContent()));
    }

    public function testDelete()
    {
      $client = static::createClient();

      $category = $this->entityManager
          ->getRepository(Category::class)
          ->findOneBy(['name' => 'edytowana kategoria']);
      $categoryId = $category->getId();

      $data = '{"name":"edytowana kategoria", "description":"nowy opis kategorii"}';
      $client->request(
        'Delete',
        "/delete_category/$categoryId",
        array(),
        array(),
        array(
          'PHP_AUTH_USER' => 'lasekdeveloper@gmail.com',
          'PHP_AUTH_PW' => 'password')
        );

      $this->assertEquals(200, $client->getResponse()->getStatusCode());
      $this->assertEquals("Kategoria została usunieta", json_decode($client->getResponse()->getContent()));
    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null; // avoid memory leaks
    }
}
