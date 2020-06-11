<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
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

    /**
    * Prince of The Ring page display
    * @Route("/prince",name="prince_index")
    * @return Response A response instance
    */
    public function prince() :Response
    {
        return $this->render('prince.html.twig');
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
