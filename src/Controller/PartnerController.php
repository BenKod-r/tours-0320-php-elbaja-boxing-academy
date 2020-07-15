<?php

namespace App\Controller;

use App\Entity\Partner;
use App\Entity\Poster;
use App\Form\PartnerType;
use App\Repository\PartnerRepository;
use App\Repository\PosterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/partner")
 */
class PartnerController extends AbstractController
{
    /**
     * Returns all partners
     * @Route("/", name="partner_index", methods={"GET"})
     */
    public function index(PartnerRepository $partnerRepository): Response
    {
        return $this->render('partner/index.html.twig', [
            'partners' => $partnerRepository->findAll(),
        ]);
    }

    /**
     * Returns a form to create an partner and redirection to choose a poster
     * @Route("/new", name="partner_new", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(Request $request): Response
    {
        $partner = new Partner();
        $form = $this->createForm(PartnerType::class, $partner);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($partner);
            $entityManager->flush();

            return $this->redirectToRoute('partner_poster_index', ['id' => $partner->getId()]);
        }

        return $this->render("partner/new.html.twig", [
            'partner' => $partner,
            'form' => $form->createView(),
        ]);
    }

    /**
     * List of all posters, choice of a poster for an partner
     * @Route("/new/{id}", name="partner_poster_index", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function choicePoster(Partner $partner, PosterRepository $posterRepository): Response
    {
        return $this->render('partner/poster.html.twig', [
            'posters' => $posterRepository->findBy([], ['date' => 'desc']),
            'partner' => $partner,
        ]);
    }

    /**
     * Add a poster to an partner and redirection to list of poster
     * @Route("/new/{partner}/poster/{poster}", name="partner_new_poster", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function addPoster(Partner $partner, Poster $poster): Response
    {
        $partner->setPoster($poster);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        
        return $this->redirectToRoute('partner_index');
    }

    /**
     * Return an partner
     * @Route("/{id}", name="partner_show", methods={"GET"})
     */
    public function show(Partner $partner): Response
    {
        return $this->render('partner/show.html.twig', [
            'partner' => $partner,
        ]);
    }


    /**
     * delete an partner
     * @Route("/{id}", name="partner_delete", methods={"DELETE"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Partner $partner): Response
    {
        if ($this->isCsrfTokenValid('delete'.$$partner->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($partner);
            $entityManager->flush();
        }

        return $this->redirectToRoute('partner_index');
    }
}
