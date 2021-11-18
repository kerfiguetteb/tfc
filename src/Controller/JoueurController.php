<?php

namespace App\Controller;

use App\Entity\Joueur;
use App\Entity\User;
use App\Form\JoueurType;
use App\Repository\JoueurRepository;
use App\Repository\EntraineurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


/**
 * @Route("/joueur")
 */
class JoueurController extends AbstractController
{
    /**
     * @Route("/", name="joueur_index", methods={"GET"})
     */
    public function index(JoueurRepository $joueurRepository, EntraineurRepository $entraineurRepository, Request $request): Response
    {
        $joueurs = $joueurRepository->findAll();

        if ($this->isGranted('ROLE_JOUEUR')) {
            $user = $this->getUser();
            //on récupere le profil joueur rattacher au compte user
            $joueur = $joueurRepository->findOneByUser($user);
            $categorie = $joueur->getCategorie();
            $joueurs = $categorie->getJoueurs();

        }elseif ($this->isGranted('ROLE_ENTRAINEUR')) {
            $user = $this->getUser();
            //on récupere le profil joueur rattacher au compte user
            $entraineur = $entraineurRepository->findOneByUser($user);
            $categorie = $entraineur->getCategories();
            $joueurs = $categorie->getJoueurs();
        }
        return $this->render('joueur/index.html.twig', [
            'joueurs' => $joueurs,
        ]);
    }

    /**
     * @Route("/new", name="joueur_new", methods={"GET","POST"})
     */
    public function new(Request $request,UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $joueur = new Joueur();
        $form = $this->createForm(JoueurType::class, $joueur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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

            return $this->redirectToRoute('joueur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('joueur/new.html.twig', [
            'joueur' => $joueur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="joueur_show", methods={"GET"})
     */
    public function show(Joueur $joueur, JoueurRepository $joueurRepository): Response
    {
        $response = $this->redirectJoueur('joueur_show', $joueur, $joueurRepository);

        if ($response) {
            return $response;
        }

        return $this->render('joueur/show.html.twig', [
            'joueur' => $joueur,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="joueur_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Joueur $joueur, JoueurRepository $joueurRepository): Response
    {
        $response = $this->redirectJoueur('joueur_show', $joueur, $joueurRepository);

        if ($response) {
            return $response;
        }

        $form = $this->createForm(JoueurType::class, $joueur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('joueur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('joueur/edit.html.twig', [
            'joueur' => $joueur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="joueur_delete", methods={"POST"})
     */
    public function delete(Request $request, Joueur $joueur): Response
    {
        if ($this->isGranted('ROLE_JOUEUR')) {
            throw new AccessDeniedException();
        }

        if ($this->isCsrfTokenValid('delete'.$joueur->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($joueur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('joueur_index', [], Response::HTTP_SEE_OTHER);
    }

    private function redirectJoueur(string $route, Joueur $joueur, JoueurRepository $joueurRepository)
    {
        // On vérifie si l'utilisateur est un student
        // Note : on peut aussi utiliser in_array('ROLE_STUDENT', $user->getRoles()) au
        // lieu de $this->isGranted('ROLE_STUDENT').
        if ($this->isGranted('ROLE_JOUEUR')) {
            // L'utilisateur est un student
            
            // Récupération du compte de l'utilisateur qui est connecté
            $user = $this->getUser();
    
            // Récupèration du profil student
            $userJoueur = $joueurRepository->findOneByUser($user);

            // Comparaison du profil demandé par l'utilisateur et le profil de l'utilisateur
            // Si les deux sont différents, on redirige l'utilisateur vers la page de son profil
            if ($joueur->getId() != $userJoueur->getId()) {
                return $this->redirectToRoute($route, [
                    'id' => $userJoueur->getId(),
                ]);
            }
        }

        // Si aucune redirection n'est nécessaire, on renvoit une valeur nulle
        return null;
    }

}
