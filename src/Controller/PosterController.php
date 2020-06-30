<?php
/**
 * Auteur: Khaled Benharrat, Damien Sarrazy, Kevin Chalumeau
 * Date: 30/06/2020
 */

namespace App\Controller;

use App\Entity\Poster;
use App\Entity\Event;
use App\Form\PosterType;
use App\Repository\PosterRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @Route("/poster")
 */
class PosterController extends AbstractController
{
    /**
    *@Route("/", name="poster_index", methods={"GET"})
    *@IsGranted("ROLE_ADMIN")
    *
    *Returns all images in the library
    */
    public function index(PosterRepository $posterRepository): Response
    {
        return $this->render('poster/index.html.twig', [
            'posters' => $posterRepository->findAll(),
        ]);
    }

    /**
     *@Route("/new", name="poster_new", methods={"GET","POST"})
     *@IsGranted("ROLE_ADMIN")
     *
     * Upload image to library, add unique name
     */
    public function new(Request $request, FileUploader $fileUploader): Response
    {
        $poster = new Poster();
        $form = $this->createForm(PosterType::class, $poster);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $posterFile */
            $posterFile = $form->get('poster_img')->getData();
            $posterSlug = $fileUploader->upload($posterFile, $poster->getFileName());
            $poster->setSlug($posterSlug);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($poster);
            $entityManager->flush();

            return $this->redirectToRoute('poster_index');
        }

        return $this->render('poster/new.html.twig', [
            'poster' => $poster,
            'form' => $form->createView(),
        ]);
    }

    /**
     *@Route("/{id}", name="poster_delete", methods={"DELETE"})
     *@IsGranted("ROLE_ADMIN")
     *
     * Delete an image and its related object
     */
    public function delete(Request $request, Poster $poster): Response
    {
        $events = $poster->getEvents();
        $partners = $poster->getPartners();
        $projects = $poster->getProjects();
        if ($this->isCsrfTokenValid('delete'.$poster->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            if (!empty($events)) {
                foreach ($events as $event) {
                    $entityManager->remove($event);
                }
            }
            if (!empty($partners)) {
                foreach ($partners as $partner) {
                    $entityManager->remove($partner);
                }
            }
            if (!empty($projects)) {
                foreach ($projects as $project) {
                    $entityManager->remove($project);
                }
            }
            unlink('../public/images/'.$poster->getSlug());
            $entityManager->remove($poster);
            $entityManager->flush();
        }

        return $this->redirectToRoute('poster_index');
    }
}
