<?php

namespace App\Controller;

use App\Entity\CommentRestaurant;
use App\Form\CommentRestaurantType;
use App\Repository\CommentRestaurantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/comment/restaurant")
 */
class CommentRestaurantController extends AbstractController
{
    /**
     * @Route("/", name="comment_restaurant_index", methods={"GET"})
     */
    public function index(CommentRestaurantRepository $commentRestaurantRepository): Response
    {
        return $this->render('comment_restaurant/index.html.twig', [
            'comment_restaurants' => $commentRestaurantRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="comment_restaurant_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $commentRestaurant = new CommentRestaurant();
        $commentRestaurant->setCommentDate(new \DateTime('now'));
        $form = $this->createForm(CommentRestaurantType::class, $commentRestaurant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commentRestaurant);
            $entityManager->flush();

            return $this->redirectToRoute('comment_restaurant_index');
        }

        return $this->render('comment_restaurant/new.html.twig', [
            'comment_restaurant' => $commentRestaurant,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="comment_restaurant_show", methods={"GET"})
     */
    public function show(CommentRestaurant $commentRestaurant): Response
    {
        return $this->render('comment_restaurant/show.html.twig', [
            'comment_restaurant' => $commentRestaurant,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="comment_restaurant_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, CommentRestaurant $commentRestaurant): Response
    {
        $form = $this->createForm(CommentRestaurantType::class, $commentRestaurant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('comment_restaurant_index');
        }

        return $this->render('comment_restaurant/edit.html.twig', [
            'comment_restaurant' => $commentRestaurant,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="comment_restaurant_delete", methods={"POST"})
     */
    public function delete(Request $request, CommentRestaurant $commentRestaurant): Response
    {
        if ($this->isCsrfTokenValid('delete' . $commentRestaurant->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($commentRestaurant);
            $entityManager->flush();
        }

        return $this->redirectToRoute('comment_restaurant_index');
    }
}
