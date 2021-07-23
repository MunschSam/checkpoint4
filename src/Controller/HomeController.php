<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Blog;
use App\Form\BlogType;
use App\Repository\BlogRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(BlogRepository $blogRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $donnees = $this->getDoctrine()->getRepository(Blog::class)->findBy([],['date' => 'desc']);

        $blog = $paginator->paginate(
            $donnees, 
            $request->query->getInt('page', 1),2
        );
        return $this->render('home/index.html.twig',[
             "blogs"=>$blog
        ]);
    }
}
