<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    /**
     * Home page display
     * @Route("/",name="home_index")
     * @return Response A response instance
     */
    public function index() :Response
    {
        return $this->render('index.html.twig');
    }

    /**
     * Partners page display
     * @Route("/partners", name="partners_index")
     * @return Response A response instance
     */
    public function partners() :Response
    {
        return $this->render('home/partners.html.twig');
    }
}
