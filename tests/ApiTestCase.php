<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use App\Entity\User;
use \App\Entity\Category;
use \App\Entity\Goal;

class ApiTestCase extends WebTestCase
{
    protected $client;
    protected $user;

    protected function setUp()
    {
      self::bootKernel();
      $this->purgeDatabase();

      $this->user = $this->createUser();
      $this->createCategory('kategoria', 'opis kategorii');
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

    protected function createCategory(String $name, String $description)
    {
      $client = $this->createAuthenticatedClient();
      $data = '{"name":"'. $name . '", "description": "'. $description .'"}';

      $client->request('Post', 'api/categories', array(), array(),array(),$data);
    }

    protected function createGoal(String $name)
    {
      $client = $this->createAuthenticatedClient();
      $data = '{"name":"' . $name . '" }';
      $categoryId = $this->getObjId('kategoria', Category::class);

      $client->request('Post', "api/categories/$categoryId/goals", array(), array(),array(),$data);
    }

    protected function createStage(String $name, String $award, String $endDate)
    {
      $client = $this->createAuthenticatedClient();
      $data = '{"name":"'. $name . '", "award": "'. $award .'", "endDate": "'. $endDate .'"}';

      if($this->getObjId('cel', Goal::class) === null){
         $this->createGoal('cel');
      }
      $goalId = $this->getObjId('cel', Goal::class);

      $client->request('Post', 'api/goals/'.$goalId.'/stages', array(), array(),array(),$data);
    }

    protected function getObjId(String $name, String $class)
    {
      $obj = $this->getEntityManager()
          ->getRepository($class)
          ->findOneByName($name);

      if($obj === null) {
          return null;
      } else {
        return $obj->getId();
      }
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
        parent::teardown();
    }
}
