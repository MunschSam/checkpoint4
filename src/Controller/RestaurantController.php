<?php

namespace App\Controller;

use App\Entity\Restaurant;
use App\Form\RestaurantType;
use App\Repository\RestaurantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\CommentRestaurant;
use App\Form\CommentRestaurantType;
use App\Repository\CommentRestaurantRepository;
use App\Service\Slugify;

/**
 * @Route("/restaurant")
 */
class RestaurantController extends AbstractController
{
    /**
     * @Route("/", name="restaurant_index", methods={"GET"})
     */
    public function index(RestaurantRepository $restaurantRepository): Response
    {
        return $this->render('restaurant/index.html.twig', [
            'restaurants' => $restaurantRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="restaurant_new", methods={"GET","POST"})
     */
    public function new(Request $request, Slugify $slugify): Response
    {
        $restaurant = new Restaurant();
        $form = $this->createForm(RestaurantType::class, $restaurant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $restaurant->setDate(new \DateTime('now'));
            $slug = $slugify->generate($restaurant->getName());
            $restaurant->setSlug($slug);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($restaurant);
            $entityManager->flush();

            return $this->redirectToRoute('restaurant_index');
        }

        return $this->render('restaurant/new.html.twig', [
            'restaurant' => $restaurant,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}", name="restaurant_show")
     */
    public function show(Restaurant $restaurant, Request $request, Slugify $slugify): Response
    {
        $commentRestaurant = new commentRestaurant();
        $commentForm = $this->createForm(CommentRestaurantType::class, $commentRestaurant);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $commentRestaurant->setCommentDate(new \DateTime());
            $commentRestaurant->setRestaurant($restaurant);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commentRestaurant);
            $entityManager->flush();

            return $this->redirectToRoute('restaurant_show', ['id' => $restaurant->getId()]);
        }

        return $this->render('restaurant/show.html.twig', [
            'restaurant' => $restaurant, 'comment_restaurant' => $commentRestaurant,
            'commentForm' => $commentForm->createView()
        ]);
    }

    /**
     * @Route("/{slug}/edit", name="restaurant_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Restaurant $restaurant, Slugify $slugify): Response
    {
        $form = $this->createForm(RestaurantType::class, $restaurant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugify->generate($restaurant->getName());
            $restaurant->setSlug($slug);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('restaurant_index');
        }

        return $this->render('restaurant/edit.html.twig', [
            'restaurant' => $restaurant,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="restaurant_delete", methods={"POST"})
     */
    public function delete(Request $request, Restaurant $restaurant): Response
    {
        if ($this->isCsrfTokenValid('delete' . $restaurant->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($restaurant);
            $entityManager->flush();
        }

        return $this->redirectToRoute('restaurant_index');
    }


    /**
     * @Route("/delete/{id}", name="comment_restaurant_delete", methods={"GET","POST"}, requirements={"id":"\d+"})
     */

    public function commentDelete(Request $request, CommentRestaurant $commentRestaurant): Response
    {
        if ($this->isCsrfTokenValid('delete' . $commentRestaurant->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($commentRestaurant);
            $entityManager->flush();
            /** @phpstan-ignore-next-line */
            return $this->redirectToRoute('restaurant_show', ['id' => $commentRestaurant->getRestaurant()->getId()]);
        }
        return $this->render('comment_restaurant/_delete.html.twig', [
            'comment_restaurant' => $commentRestaurant,
        ]);
    }
}
