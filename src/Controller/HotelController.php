<?php

namespace App\Controller;

use App\Entity\Hotel;
use App\Form\HotelType;
use App\Repository\HotelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\CommentHotel;
use App\Form\CommentHotelType;
use App\Repository\CommentHotelRepository;

/**
 * @Route("/hotel")
 */
class HotelController extends AbstractController
{
    /**
     * @Route("/", name="hotel_index", methods={"GET"})
     */
    public function index(HotelRepository $hotelRepository): Response
    {
        return $this->render('hotel/index.html.twig', [
            'hotels' => $hotelRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="hotel_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $hotel = new Hotel();
        $form = $this->createForm(HotelType::class, $hotel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hotel->setDate(new \DateTime('now'));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($hotel);
            $entityManager->flush();

            return $this->redirectToRoute('hotel_index');
        }

        return $this->render('hotel/new.html.twig', [
            'hotel' => $hotel,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="hotel_show")
     */
    public function show(Hotel $hotel, Request $request): Response
    {
        $commentHotel = new commentHotel();
        $commentForm = $this->createForm(CommentHotelType::class, $commentHotel);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $commentHotel->setCommentDate(new \DateTime());
            $commentHotel->setHotel($hotel);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commentHotel);
            $entityManager->flush();

            return $this->redirectToRoute('hotel_show', ['id' => $hotel->getId()]);
        }

        return $this->render('hotel/show.html.twig', [
            'hotel' => $hotel, 'comment_hotel' => $commentHotel, 'commentForm' => $commentForm->createView()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="hotel_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Hotel $hotel): Response
    {
        $form = $this->createForm(HotelType::class, $hotel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('hotel_index');
        }

        return $this->render('hotel/edit.html.twig', [
            'hotel' => $hotel,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="hotel_delete", methods={"POST"})
     */
    public function delete(Request $request, Hotel $hotel): Response
    {
        if ($this->isCsrfTokenValid('delete' . $hotel->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($hotel);
            $entityManager->flush();
        }

        return $this->redirectToRoute('hotel_index');
    }

    /**
     * @Route("/delete/{id}", name="comment_hotel_delete", methods={"GET","POST"}, requirements={"id":"\d+"})
     */

    public function commentDelete(Request $request, CommentHotel $commentHotel): Response
    {
        if ($this->isCsrfTokenValid('delete' . $commentHotel->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($commentHotel);
            $entityManager->flush();
            return $this->redirectToRoute('hotel_show', ['id' => $commentHotel->getHotel()->getId()]);
        }
        return $this->render('comment_hotel/_delete.html.twig', [
            'comment_hotel' => $commentHotel,
        ]);
    }
}
