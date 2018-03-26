<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use App\Entity\User;
use \App\Entity\Category;

class ApiTestCase extends WebTestCase
{
    protected $client;

    public static function setUpBeforeClass()
    {
        self::bootKernel();
    }

    protected function setUp()
    {
      $this->createUser();
      $this->createCategory();
    }

    protected function createUser($email = 'lasekdeveloper@gmail.com', $plainPassword = 'password')
    {
        $user = new User();
        $user->setEmail($email);
        $password = $this->getService('security.password_encoder')
          ->encodePassword($user, $plainPassword);
        $user->setPassword($password);
        $user->setIsActive(true);
        $user->generateActivationCode();

        $em = $this->getEntityManager();
        $em->persist($user);
        $em->flush();

        return $user;
    }

    protected function createCategory()
    {
      $client = $this->createAuthenticatedClient();
      $data = '{"name":"kategoria", "description":"opis kategorii"}';

      $client->request('Post', 'api/new_category', array(), array(),array(),$data);
    }

    protected function getCategoryId()
    {
      $category = $this->getEntityManager()
          ->getRepository(Category::class)
          ->findOneBy(['name' => 'kategoria']);

      return $category->getId();
    }

    protected function createAuthenticatedClient($email = 'lasekdeveloper@gmail.com', $password = 'password')
    {
        $credentials = '{"email":"'.$email.'","password":"'.$password.'"}';
        $client = static::createClient();
        $client->request(
            'POST',
            'api/login_check',
            array(),
            array(),
            array(
              'CONTENT_TYPE' => 'application/json',
            ),
            $credentials
        );
        $data = json_decode($client->getResponse()->getContent(), true);

        $client = static::createClient();
        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));

        return $client;
   }


    protected function getService($id)
    {
        return self::$kernel->getContainer()->get($id);
    }

    protected function getEntityManager()
    {
        return $this->getService('doctrine.orm.entity_manager');
    }

    protected function purgeDatabase()
    {
        $purger = new ORMPurger($this->getService('doctrine')->getManager());
        $purger->purge();
    }

    protected function tearDown()
    {
        $this->purgeDatabase();
    }
}