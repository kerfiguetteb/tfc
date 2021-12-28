<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Picture;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Service\PostSearchFormViewFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/post")
 */

class PostController extends AbstractController
{
    /**
     * @Route("/", name="post_index", methods={"GET"})
     */
    public function index(PostRepository $postRepository,PostSearchFormViewFactory $postSearchFormViewFactory, Request $request): Response
    {       

        return $this->render('post/index.html.twig', [
            'posts' => $postRepository->findBy([],['id' => 'DESC']),
        ]);
    }


    /**
     * @Route("/{id}", name="post_show", methods={"GET"})
     */
    public function show(Post $post): Response
    {
        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }
   
}
