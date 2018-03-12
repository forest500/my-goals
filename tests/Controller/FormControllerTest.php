<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FormControllerTest extends WebTestCase
{
    /** @test */
    public function login_route()
    {
        $client = static::createClient();

        $client->request('GET', '/login');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /** @test */
    public function that_after_log_in_is_redirected_to_homepage()
    {
        $client = static::createClient();
        $client->followRedirects();
        $crawler = $client->request('GET', '/login');
        $buttonCrawlerNode = $crawler->selectButton('submit');

        $form = $buttonCrawlerNode->form();
        $form['login[email]'] = 'lasekdeveloper@gmail.com';
        $form['login[password]'] = 'password';

        $crawler = $client->submit($form);

        $this->assertContains('Welcome!', $client->getResponse()->getContent());
    }
}
