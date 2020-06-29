<?php
namespace App\Controller;

use App\Model\Contact;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

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

    /**
     * @Route("/email")
     */
    public function sendEmail(MailerInterface $mailer)
    {
        $email = (new Email())
            ->from('')
            ->to('elbajaboxingacademytest@gmail.com')
            ->subject('Message provenant du site internet')
        ;

        $mailer->send($email);
    }
}
