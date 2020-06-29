<?php
namespace App\Controller;

use App\Model\Contact;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;

class ContactController extends AbstractController
{
    /**
     * Contact page display
     * @Route("/contact",name="contact_index",  methods={"GET"})
     * @return Response A reponse instance
     */
    public function contact(): Response
    {
        return $this->render('contact/index.html.twig');
    }
}
