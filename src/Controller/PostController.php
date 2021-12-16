<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Picture;
use App\Form\PostType;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    /**
     * @Route("/", name="post_index", methods={"GET"})
     */
    public function index(PaginatorInterface $paginator, PostRepository $postRepository, Request $request): Response
    {       
        $qb = $postRepository->findBy([],['id' => 'DESC']);;

        $pagination = $paginator->paginate(
            $qb, /* query builder NOT result */
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );

        return $this->render('post/index.html.twig', [
            'posts' => $pagination,
        ]);
    }

//     /**
//      * @Route("/new", name="post_new", methods={"GET","POST"})
//      */
//     public function new(Request $request): Response
//     {
//         $post = new Post();
//         $form = $this->createForm(PostType::class, $post);
//         $form->handleRequest($request);

//         if ($form->isSubmitted() && $form->isValid()) {
//             $entityManager = $this->getDoctrine()->getManager();
//             $entityManager->persist($post);
//             $entityManager->flush();

//             return $this->redirectToRoute('post_index', [], Response::HTTP_SEE_OTHER);
//         }

//         return $this->render('post/new.html.twig', [
//             'post' => $post,
//             'form' => $form->createView(),
//         ]);
//     }

//     /**
//      * @Route("/{id}", name="post_show", methods={"GET"})
//      */
//     public function show(Post $post): Response
//     {
//         return $this->render('post/show.html.twig', [
//             'post' => $post,
//         ]);
//     }

//     /**
//      * @Route("/{id}/edit", name="post_edit", methods={"GET","POST"})
//      */
//     public function edit(Request $request, Post $post): Response
//     {
//         $form = $this->createForm(PostType::class, $post);
//         $form->handleRequest($request);

//         if ($form->isSubmitted() && $form->isValid()) {
//             $this->getDoctrine()->getManager()->flush();

//             return $this->redirectToRoute('post_index', [], Response::HTTP_SEE_OTHER);
//         }

//         return $this->render('post/edit.html.twig', [
//             'post' => $post,
//             'form' => $form->createView(),
//         ]);
//     }

//     /**
//      * @Route("/{id}", name="post_delete", methods={"POST"})
//      */
//     public function delete(Request $request, Post $post): Response
//     {
//         if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token'))) {
//             $entityManager = $this->getDoctrine()->getManager();
//             $entityManager->remove($post);
//             $entityManager->flush();
//         }

//         return $this->redirectToRoute('post_index', [], Response::HTTP_SEE_OTHER);
//     }
   
//     /**
//      * @Route("/{id}", name="picture_delete", methods={"POST"})
//      */
//     public function deletePicture(Request $request, Picture $picture): Response
//     {
//         if ($this->isCsrfTokenValid('delete'.$picture->getId(), $request->request->get('_token'))) {
//             $entityManager = $this->getDoctrine()->getManager();
//             $entityManager->remove($picture);
//             $entityManager->flush();
//         }

//         return $this->redirectToRoute('post_index', [], Response::HTTP_SEE_OTHER);
//     }
}
