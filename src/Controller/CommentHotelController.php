<?php

namespace App\Controller;

use App\Entity\CommentHotel;
use App\Form\CommentHotelType;
use App\Repository\CommentHotelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/comment/hotel")
 */
class CommentHotelController extends AbstractController
{
    /**
     * @Route("/", name="comment_hotel_index", methods={"GET"})
     */
    public function index(CommentHotelRepository $commentHotelRepository): Response
    {
        return $this->render('comment_hotel/index.html.twig', [
            'comment_hotels' => $commentHotelRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="comment_hotel_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $commentHotel = new CommentHotel();
        $commentHotel->setCommentDate(new \DateTime('now'));
        $form = $this->createForm(CommentHotelType::class, $commentHotel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commentHotel);
            $entityManager->flush();

            return $this->redirectToRoute('comment_hotel_index');
        }

        return $this->render('comment_hotel/new.html.twig', [
            'comment_hotel' => $commentHotel,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="comment_hotel_show", methods={"GET"})
     */
    public function show(CommentHotel $commentHotel): Response
    {
        return $this->render('comment_hotel/show.html.twig', [
            'comment_hotel' => $commentHotel,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="comment_hotel_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, CommentHotel $commentHotel): Response
    {
        $form = $this->createForm(CommentHotelType::class, $commentHotel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('comment_hotel_index');
        }

        return $this->render('comment_hotel/edit.html.twig', [
            'comment_hotel' => $commentHotel,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="comment_hotel_delete", methods={"POST"})
     */
    public function delete(Request $request, CommentHotel $commentHotel): Response
    {
        if ($this->isCsrfTokenValid('delete' . $commentHotel->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($commentHotel);
            $entityManager->flush();
        }

        return $this->redirectToRoute('comment_hotel_index');
    }
}
