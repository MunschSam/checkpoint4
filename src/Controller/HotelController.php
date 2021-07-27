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
use App\Service\Slugify;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/hotel")
 */
class HotelController extends AbstractController
{
    /**
     * @Route("/", name="hotel_index", methods={"GET"})
     */
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $donnees = $this->getDoctrine()->getRepository(Hotel::class)->findBy([],['date' => 'desc']);

        $hotel = $paginator->paginate(
            $donnees, 
            $request->query->getInt('page', 1),3
        );

        return $this->render('hotel/index.html.twig', [
            'hotels' => $hotel,
        ]);
    }

    /**
     * @Route("/new", name="hotel_new", methods={"GET","POST"})
     */
    public function new(Request $request, Slugify $slugify): Response
    {
        $hotel = new Hotel();
        $form = $this->createForm(HotelType::class, $hotel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hotel->setDate(new \DateTime('now'));
            $slug = $slugify->generate($hotel->getName());
            $hotel->setSlug($slug);
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
     * @Route("/{slug}", name="hotel_show")
     */
    public function show(Hotel $hotel, Request $request, Slugify $slugify): Response
    {
        $commentHotel = new commentHotel();
        $commentForm = $this->createForm(CommentHotelType::class, $commentHotel);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $commentHotel->setCommentDate(new \DateTime());
            $commentHotel->setHotel($hotel);
            $commentHotel->setAuteur($this->getUser());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commentHotel);
            $entityManager->flush();

            return $this->redirectToRoute('hotel_show', ['slug' => $hotel->getSlug()]);
        }

        return $this->render('hotel/show.html.twig', [
            'hotel' => $hotel, 'comment_hotel' => $commentHotel, 'commentForm' => $commentForm->createView()
        ]);
    }

    /**
     * @Route("/{slug}/edit", name="hotel_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Hotel $hotel, Slugify $slugify): Response
    {
        $form = $this->createForm(HotelType::class, $hotel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugify->generate($hotel->getName());
            $hotel->setSlug($slug);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('hotel_index');
        }

        return $this->render('hotel/edit.html.twig', [
            'hotel' => $hotel,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/deleteHotel/{id}", name="hotel_delete", methods={"POST"})
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
            return $this->redirectToRoute('hotel_show', ['slug' => $commentHotel->getHotel()->getSlug()]);
        }
        return $this->render('comment_hotel/_delete.html.twig', [
            'comment_hotel' => $commentHotel,
        ]);
    }
}
