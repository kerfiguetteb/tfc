<?php

namespace App\Controller;

use App\Entity\Entraineur;
use App\Form\EntraineurType;
use App\Repository\EntraineurRepository;
use App\Repository\JoueurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/entraineur")
 */
class EntraineurController extends AbstractController
{
    /**
     * @Route("/", name="entraineur_index", methods={"GET"})
     */
    public function index(EntraineurRepository $entraineurRepository, JoueurRepository $joueurRepository): Response
    {
        
        
        if ($this->isGranted('ROLE_ENTRAINEUR')) {
            $user = $this->getUser();
            //on récupere le profil joueur rattacher au compte user
            $entraineur = $entraineurRepository->findOneByUser($user);
            $categorie = $entraineur->getCategories();
            $section = $categorie->getSection();
            $categorieName = $categorie->getNom();
            $groupe = $categorie->getGroupe();

            $joueurs = $joueurRepository->findBySectionGroupeName($section,$groupe,$categorieName);
            dump($joueurs);
        }

        return $this->render('entraineur/joueur/index.html.twig', [
            'joueurs'=> $joueurs,
        ]);
    }

    /**
     * @Route("/{id}", name="entraineur_show", methods={"GET"})
     */
    public function show(Entraineur $entraineur): Response
    {
        return $this->render('entraineur/show.html.twig', [
            'entraineur' => $entraineur,
        ]);
    }


}


