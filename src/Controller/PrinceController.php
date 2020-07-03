<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PrinceController extends AbstractController
{
    /**
     * Prince of The Ring page display
     * @Route("/prince",name="prince_index")
     * @return Response A response instance
     */
    public function prince() :Response
    {
        return $this->render('prince/index.html.twig');
    }
}
