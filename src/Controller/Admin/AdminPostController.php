<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Entity\Picture;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Service\PostSearchFormViewFactory;
use App\Service\ChoicesFormViewFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


    /**
     * @Route("/admin/post")
     */

class AdminPostController extends AbstractController
{

    /**
     * @Route("/", name="admin_post_index", methods={"GET","POST"})
     */
    public function index(PostRepository $postRepository,PostSearchFormViewFactory $postSearchFormViewFactory, Request $request): Response
    {       
        return $this->render('admin/post/index.html.twig', [
            'posts' => $postRepository->findBy([],['id' => 'DESC']),
        ]);
    }

    /**
     * @Route("/new", name="admin_post_create", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('admin_post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/post/new.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/{id}", name="admin_post_show", methods={"GET"})
     */
    public function show(Post $post): Response
    {
        return $this->render('admin/post/show.html.twig', [
            'post' => $post,
        ]);
    }


    /**
     * @Route("{id}/edit", name="admin_post_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Post $post, ChoicesFormViewFactory $choicesFormViewFactory): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/post/edit.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}", name="admin_post_delete", methods={"DELETE"})
     */
    public function delete(Post $post, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete' . $post->getId(), $request->get('_token'))){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($post);
            $entityManager->flush();
            $this->addFlash('success', 'Bien supprimé avec succès');
        }
        return $this->redirectToRoute('admin_post_index', [], Response::HTTP_SEE_OTHER);
}
   
}
