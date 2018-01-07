<?php

namespace App\Controller;


use FOS\RestBundle\Controller\FOSRestController;

class HomeController extends FOSRestController
{
    /**
     * @Route("/", name="homepage")
     */
    public function home(Request $request)
    {
        return $this->render(
            'home.html.twig',
            array()
        );
    }
}
