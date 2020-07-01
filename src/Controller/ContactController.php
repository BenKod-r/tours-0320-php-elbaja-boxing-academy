<?php
namespace App\Controller;

use App\Model\Contact;
use App\Form\ContactType;
use App\Notification\ContactNotification;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bundle\FrameworkBundle\Controller\ControllerTrait;

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
    public function new(Request $request, MailerInterface $mailer, ContactNotification $notification): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $notification->notify($contact);
            return $this->redirectToRoute('base.html.twig');
        }
        return $this->render('contact/index.html.twig', [
            'contact' => $contact,
            'form' => $form->createView(),
        ]);
    }
}
