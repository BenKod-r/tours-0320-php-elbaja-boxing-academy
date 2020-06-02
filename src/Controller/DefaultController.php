<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * Home page display
     * @Route("/",name="app_index")
     * @return Response A response instance
     */
    public function index() :Response
    {
        return $this->render('home.html.twig');
    }
}
