<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Blog;
use App\Form\BlogType;
use App\Repository\BlogRepository;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(BlogRepository $blogRepository): Response
    {
        return $this->render('home/index.html.twig',[
            'blogs' => $blogRepository->findAll(),
        ]);
    }
}
