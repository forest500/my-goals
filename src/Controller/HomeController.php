<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Form\StageType;
use App\Entity\Stage;

class HomeController extends Controller
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
