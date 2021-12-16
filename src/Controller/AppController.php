<?php

namespace App\Controller;

use App\Form\JoueurSearchType;
use App\Repository\JoueurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    /**
     * @Route("/app", name="app")
     */
    public function index(): Response
    {
        return $this->render('app/index.html.twig', [
            'controller_name' => 'AppController',
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

        // On vérifie si l'utilisateur est un joueur
        // Note : on peut aussi utiliser in_array('ROLE_joueur', $user->getRoles())
        // au lieu de $this->isGranted('ROLE_joueur').
        // if ($this->isGranted('ROLE_STUDENT')) {
        //     // L'utilisateur est un student

        //     // On récupère le compte de l'utilisateur authentifié
        //     $user = $this->getUser();

        //     // On récupère le profil student lié au compte utilisateur
        //     $student = $studentRepository->findOneByUser($user);

        //     // On récupère la school year de l'utilisateur
        //     $schoolYear = $student->getSchoolYear();

        //     // On récupère la liste des students de la school year
        //     $schoolYearStudents = $schoolYear->getStudents();

        //     // Le student n'a le droit de voir que les students de la même school year.
        //     // On va donc croiser le résultat de la recherche avec les students de la
        //     // school year.

        //     // Initialisation de la liste filtrée (qui est d'abord vide)
        //     $filteredStudents = [];

        //     // On passe revue chaque student du résultat de recherche et on vérifie
        //     // s'il fait partie de la school year.
        //     foreach ($students as $student) {
        //         // Si le student passé en revue fait partie de la school year, on
        //         // l'ajoute à la liste filtrée.
        //         if ($schoolYearStudents->contains($student)) {
        //             $filteredStudents[] = $student;
        //         }
        //     }

        //     // On écrase la liste originale avec le contenu de la liste filtrée.
        //     $students = $filteredStudents;
        // }

        return $this->render('admin/joueur/index.html.twig', [
            'joueurs' => $joueurs,
            'joueurSearchForm' => $joueurSearchForm->createView(),
        ]);
    }

}
