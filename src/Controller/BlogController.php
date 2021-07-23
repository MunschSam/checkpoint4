<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Form\BlogType;
use App\Repository\BlogRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\CommentBlog;
use App\Form\CommentBlogType;
use App\Repository\CommentBlogRepository;
use App\Service\Slugify;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/blog")
 */
class BlogController extends AbstractController
{
    /**
     * @Route("/", name="blog_index", methods={"GET"})
     */
    public function index(BlogRepository $blogRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $donnees = $this->getDoctrine()->getRepository(Blog::class)->findBy([],['date' => 'desc']);

        $blog = $paginator->paginate(
            $donnees, 
            $request->query->getInt('page', 1),6
        );
        return $this->render('blog/index.html.twig', [
            'blogs' => $blog,
        ]);
    }

    /**
     * @Route("/new", name="blog_new", methods={"GET","POST"})
     */
    public function new(Request $request, Slugify $slugify): Response
    {
        $blog = new Blog();
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $blog->setDate(new \DateTime('now'));
            $slug = $slugify->generate($blog->getSujet());
            $blog->setSlug($slug);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($blog);
            $entityManager->flush();

            return $this->redirectToRoute('blog_index');
        }

        return $this->render('blog/new.html.twig', [
            'blog' => $blog,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}", name="blog_show")
     */
    public function show(Blog $blog, Slugify $slugify, Request $request): Response
    {
        $commentBlog = new commentBlog();
        $commentForm = $this->createForm(CommentBlogType::class, $commentBlog);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $commentBlog->setCommentDate(new \DateTime());
            $commentBlog->setBlog($blog);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commentBlog);
            $entityManager->flush();

            return $this->redirectToRoute('blog_show', ['slug' => $blog->getSlug()]);
        }
       
        return $this->render('blog/show.html.twig', [
            'blog' => $blog, 'comment_blog' => $commentBlog, 'commentForm' => $commentForm->createView()
        ]);
    }

    /**
     * @Route("/{slug}/edit", name="blog_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Blog $blog, Slugify $slugify): Response
    {
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugify->generate($blog->getSujet());
            $blog->setSlug($slug);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('blog_index');
        }

        return $this->render('blog/edit.html.twig', [
            'blog' => $blog,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="blog_delete", methods={"POST"})
     */
    public function delete(Request $request, Blog $blog): Response
    {
        if ($this->isCsrfTokenValid('delete' . $blog->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($blog);
            $entityManager->flush();
            
        }

        return $this->redirectToRoute('blog_index');
    }
}
