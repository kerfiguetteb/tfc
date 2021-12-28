<?php

namespace App\Controller\Entraineur;

use App\Entity\Joueur;
use App\Repository\JoueurRepository;
use App\Repository\EntraineurRepository;
use App\Repository\CategorieRepository;
use Symfony\Component\Validator\Constraints\DateTime;
use App\Form\EntraineurJoueurType;
use App\Form\JoueurType;
use App\Repository\EquipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use App\Controller\AlgoCategorieTrait;

/**
 * @Route("entraineur/joueur")
 */

 class EntraineurJoueurController extends AbstractController
 {
    use AlgoCategorieTrait;

    /**
     * @Route("/", name="entraineur_joueur_index", methods={"GET"})
     */
    public function index(EntraineurRepository $entraineurRepository, JoueurRepository $joueurRepository): Response
    {
            $user = $this->getUser();
            //on récupere le profil joueur rattacher au compte user
            $entraineur = $entraineurRepository->findOneByUser($user);
            $categorie = $entraineur->getCategories();
            $section = $categorie->getSection();
            $categorieName = $categorie->getNom();
            $groupe = $categorie->getGroupe();

            $joueurs = $joueurRepository->findBySectionGroupeName($section,$groupe,$categorieName);
            dump($joueurs);
        

        return $this->render('entraineur/joueur/index.html.twig', [
            'joueurs'=> $joueurs,
        ]);
    }

        /**
     * @Route("/new", name="entraineur_joueur_new", methods={"GET","POST"})
     */
    public function new(Request $request,UserPasswordEncoderInterface $passwordEncoder,JoueurRepository $joueurRepository,
    EquipeRepository $equipeRepository,
    CategorieRepository $categorieRepository): Response
    {
        $joueur = new Joueur();
        $form = $this->createForm(JoueurType::class, $joueur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sexe = $form->getData()->getSexe();
            $birthday = $form->getData()->getDateDeNaissance();
            $today = new \DateTime(date('Y-m-d H:i:s'));
            $age = $birthday->diff($today);
            $equipe = $equipeRepository->find(1);
            $tilloy = $equipe;


            $this->CategoriOfPlayer($sexe,$age,$joueurRepository,$equipeRepository,$categorieRepository,$joueur,$tilloy);
            $user = $joueur->getUser();

            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('user')->get('plainPassword')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($joueur);
            $entityManager->flush();

            return $this->redirectToRoute('entraineur_joueur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('entraineur/joueur/new.html.twig', [
            'joueur' => $joueur,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}", name="entraineur_joueur_show", methods={"GET"})
     */
    public function show(Joueur $joueur, JoueurRepository $joueurRepository): Response
    {

        $birthday = $joueur->getDateDeNaissance();
        $today = new \DateTime(date('Y-m-d H:i:s'));
        $ageDiff = $birthday->diff($today);
        $age = $ageDiff->format('%y');
        $user = $this->getUser();
        $userJoueur = $joueur->getUser();;

        return $this->render('entraineur/joueur/show.html.twig', [
            'joueur' => $joueur,
            'age' => $age,
            'userJoueur' => $userJoueur,
            'user' => $user
        ]);
    }



    
    /**
     * @Route("/{id}/edit", name="entraineur_joueur_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Joueur $joueur, JoueurRepository $joueurRepository,
    EquipeRepository $equipeRepository,
    CategorieRepository $categorieRepository): Response
    {
        $form = $this->createForm(EntraineurJoueurType::class, $joueur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sexe = $form->getData()->getSexe();
            $birthday = $form->getData()->getDateDeNaissance();
            $today = new \DateTime(date('Y-m-d H:i:s'));
            $age = $birthday->diff($today);
            $equipe = $equipeRepository->find(1);
            $tilloy = $equipe;
            
            // $this->CategoriOfPlayer($sexe,$age,$joueurRepository,$equipeRepository,$categorieRepository,$joueur,$tilloy);

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('entraineur_joueur_index', [], Response::HTTP_SEE_OTHER);

        }

        return $this->render('entraineur/joueur/edit.html.twig', [
            'joueur' => $joueur,
            'form' => $form->createView(),
        ]);
    }


 }

