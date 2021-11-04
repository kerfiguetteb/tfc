<?php

namespace App\Controller;

use App\Entity\Equipe;
use App\Repository\EquipeRepository;
use App\Entity\Domicile;
use App\Repository\DomicileRepository;
use App\Entity\Joueur;
use App\Repository\JoueurRepository;
use App\Entity\Section;
use App\Repository\SectionRepository;
use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use App\Entity\Groupe;
use App\Repository\GroupeRepository;
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
        DomicileRepository $domicileRepository, 
        JoueurRepository $joueurRepository,
        SectionRepository $sectionRepository,
        CategorieRepository $categorieRepository,
        GroupeRepository $groupeRepository
        ): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $point = -100;
        $equipe = $equipeRepository->findByPointAndDiff($point);
        // dump($equipe);

        $equipe = $equipeRepository->find(1);
        $equipe->getDomicile();
        // dump($equipe);
        


        $joueur = $joueurRepository->findAll();
        // la liste des joueur dont la section est feminine
        $joueurF = $sectionRepository->find(2);
        $senior = $categorieRepository->find(6);
        $groupeA = $groupeRepository->find(1);
        $seniorFA = $joueurRepository->findBySectionByCategorieByGroupe($joueurF,$senior,$groupeA);
        dump($seniorFA);        



        // les joueur de la section feminine dont la categories est senior 
    
        exit();
    }
}
