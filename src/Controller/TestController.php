<?php

namespace App\Controller;

use App\Entity\Equipe;
use App\Repository\EquipeRepository;
use App\Entity\Domicile;
use App\Repository\DomicileRepository;
use App\Entity\Joueur;
use App\Repository\JoueurRepository;
use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use App\Entity\Entraineur;
use App\Repository\EntraineurRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     * @Route("/test", name="test")
     */
    public function index(
        EquipeRepository $equipeRepository, 
        EntraineurRepository $entraineurRepository, 
        DomicileRepository $domicileRepository, 
        JoueurRepository $joueurRepository,
        CategorieRepository $categorieRepository,
        PostRepository $postRepository
        ): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $point = -100;
        $equipe = $equipeRepository->findByPointAndDiff($point);
        // dump($equipe);

        $equipe = $equipeRepository->find(1);
        $equipe->getDomicile();
        dump($equipe);
        
        $categories = $categorieRepository->findAll();
        // dump($categories);


        // $categorie = $categorieRepository->findByGroupeAndsection('A','Masculine');
        // dump($categorie);
    
        $joueurs = $joueurRepository->findBySectionGroupeName('Masculine','A','U8-U9');
        $posts = $postRepository->findBySectionGroupeName('Feminine','C','Veteran');
        dump($posts);

        $categorieId = 26;
        $entraineur = $entraineurRepository->findByCategorie($categorieId);
        dump($entraineur);

        exit();
    }
}
