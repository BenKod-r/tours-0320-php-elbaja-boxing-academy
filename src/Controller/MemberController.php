<?php
/**
 * Auteur: Khaled Benharrat, Damien Sarrazy, Kevin Chalumeau
 * Date: 30/06/2020
 */

namespace App\Controller;

use App\Entity\Member;
use App\Repository\MemberRepository;
use App\Entity\Poster;
use App\Repository\PosterRepository;
use App\Form\MemberType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/member")
 */
class MemberController extends AbstractController
{
    /**
     * Returns all members
     * @Route("/", name="member_index", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(MemberRepository $memberRepository): Response
    {
        return $this->render('member/index.html.twig', [
            'members' => $memberRepository->findBy([], ['date' => 'desc']),
        ]);
    }

    /**
     * Returns a form to create a member and redirection to choose a poster
     * @Route("/new", name="member_new", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(Request $request): Response
    {
        $member = new Member();
        $form = $this->createForm(MemberType::class, $member);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($member);
            $entityManager->flush();

            return $this->redirectToRoute('member_poster_index', ['id' => $member->getId()]);
        }

        return $this->render('member/new.html.twig', [
            'member' => $member,
            'form' => $form->createView(),
        ]);
    }

    /**
     * List of all posters, choice of a poster for a member
     * @Route("/new/{id}", name="member_poster_index", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function choicePoster(Member $member, PosterRepository $posterRepository): Response
    {
        return $this->render('member/poster.html.twig', [
            'posters' => $posterRepository->findBy([], ['date' => 'desc']),
            'member' => $member,
        ]);
    }

    /**
     * Add a poster to a member and redirection to list of poster
     * @Route("/new/{member}/poster/{poster}", name="member_new_poster", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function addPoster(Member $member, Poster $poster): Response
    {
        $member->setPoster($poster);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        
        return $this->redirectToRoute('member_index');
    }

    /**
     * Returns a member and a form to edit the member
     * @Route("/{id}/edit", name="member_edit", methods={"GET","POST"},requirements={"id": "\d+"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Member $member): Response
    {
        $form = $this->createForm(MemberType::class, $member);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('member_index');
        }

        return $this->render('member/edit.html.twig', [
            'member' => $member,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Delete a member
     * @Route("/{id}", name="member_delete", methods={"DELETE"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Member $member): Response
    {
        if ($this->isCsrfTokenValid('delete'.$member->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($member);
            $entityManager->flush();
        }

        return $this->redirectToRoute('member_index');
    }
}
