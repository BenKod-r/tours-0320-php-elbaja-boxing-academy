<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HallOfFameController extends AbstractController
{
    /**
     * Hall of Fame page display
     * @Route("/hall",name="hall_index")
     * @return Response A response instance
     */
    public function hall() :Response
    {
        return $this->render('hall/index.html.twig');
    }
}
