<?php
/**
 * Auteur: Khaled Benharrat, Damien Sarrazy, Kevin Chalumeau
 * Date: 30/06/2020
 */

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Poster;
use App\Repository\PosterRepository;
use App\Form\EventType;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/event")
 */
class EventController extends AbstractController
{
    /**
     * Returns all events
     * 
     * @Route("/", name="event_index", methods={"GET"})
     */
    public function index(EventRepository $eventRepository): Response
    {
        return $this->render('event/index.html.twig', [
            'events' => $eventRepository->findBy([], ['id' => 'desc']),
        ]);
    }

    /**
     * Returns a form to create an event and redirection to choose a poster
     * 
     * @Route("/new", name="event_new", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(Request $request): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('event_poster_index', ['id' => $event->getId()]);
        }

        return $this->render('event/new.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }

    /**
     * List of all posters, choice of a poster for an event
     * 
     * @Route("/new/{id}", name="event_poster_index", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function choicePoster(Event $event, PosterRepository $posterRepository): Response
    {
        return $this->render('event/poster.html.twig', [
            'posters' => $posterRepository->findBy([], ['id' => 'desc']),
            'event' => $event,
        ]);
    }

    /**
     * Add a poster to an event and redirection to list of poster
     * 
     * @Route("/new/{event}/poster/{poster}", name="event_new_poster", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function addPoster(Event $event, Poster $poster): Response
    {
        $event->setPoster($poster);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        
        return $this->redirectToRoute('event_index');
    }

    /**
     * Return an event
     * 
     * @Route("/{id}", name="event_show", methods={"GET"})
     */
    public function show(Event $event): Response
    {
        return $this->render('event/show.html.twig', [
            'event' => $event,
        ]);
    }

    /**
     * Returns an event and a form to edit the event
     * 
     * @Route("/{id}/edit", name="event_edit", methods={"GET","POST"},requirements={"id": "\d+"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Event $event): Response
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('event_index');
        }

        return $this->render('event/edit.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Delete an event
     * 
     * @Route("/{id}", name="event_delete", methods={"DELETE"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Event $event): Response
    {
        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($event);
            $entityManager->flush();
        }

        return $this->redirectToRoute('event_index');
    }
}
