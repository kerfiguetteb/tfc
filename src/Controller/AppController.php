<?php

namespace App\Controller;

use App\Form\JoueurSearchType;
use App\Form\PostSearchType;
use App\Repository\JoueurRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/")
 */

class AppController extends AbstractController
{
    /**
     * @Route("/search", name="app_search_post", methods={"GET","POST"})
     */
    public function index(Request $request, PostRepository $postRepository): Response
    {
        $posts = [];
        $postSearchForm = $this->createForm(PostSearchType::class);
        $postSearchForm->handleRequest($request);
        
        if ($postSearchForm->isSubmitted() && $postSearchForm->isValid()) {
            $section = $postSearchForm->getData()['section'];
            $groupe = $postSearchForm->getData()['groupe'];
            $categorie = $postSearchForm->getData()['categorie'];
            $posts = $postRepository->findBySectionGroupeName($section,$groupe,$categorie);
        }


        // if ($this->isGranted('ROLE_ADMIN')) {
        //     return $this->render('admin/post/index.html.twig', [
        //         'posts' => $posts,
        //         'postsSearchForm' => $postSearchForm->createView(),
        //     ]);
        // }
            return $this->render('post/index.html.twig', [
                'posts' => $posts,
                'postsSearchForm' => $postSearchForm->createView(),
            ]);
        
    }
    
    /**
     * @Route("admin/search", name="app_search", methods={"GET","POST"})
     */

    public function search(Request $request, JoueurRepository $joueurRepository): Response
    {
        // Initialisation du résultat de la recherche.
        // On part d'une liste vide. 
        $joueurs = [];

        // Création du formulaire de recherche
        $joueurSearchForm = $this->createForm(JoueurSearchType::class);
        $joueurSearchForm->handleRequest($request);

        if ($joueurSearchForm->isSubmitted() && $joueurSearchForm->isValid()) {
            $section = $joueurSearchForm->getData()['section'];
            $groupe = $joueurSearchForm->getData()['groupe'];
            $categorie = $joueurSearchForm->getData()['categorie'];
            $joueurs = $joueurRepository->findBySectionGroupeName($section,$groupe,$categorie);
        }


        return $this->render('admin/joueur/index.html.twig', [
            'joueurs' => $joueurs,
            'joueurSearchForm' => $joueurSearchForm->createView(),
        ]);
    }

}
