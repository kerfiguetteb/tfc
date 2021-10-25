<?php

namespace App\Controller;

use App\Entity\Joueur;
use App\Entity\User;
use App\Form\JoueurType;
use App\Repository\JoueurRepository;
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
     * @Route("/", name="joueur_index", methods={"GET", "POST"})
     */
    public function index(Request $request, JoueurRepository $joueurRepository): Response
    {
        $joueurs = $joueurRepository->findAll();

        // On vérifie si l'utilisateur est un joueur
        // Note : on peut aussi utiliser in_array('ROLE_JOUEUR', $user->getRoles())
        // au lieu de $this->isGranted('ROLE_JOUEUR').
        if ($this->isGranted('ROLE_JOUEUR')) {
            // L'utilisateur est un joueur

            // On récupère le compte de l'utilisateur authentifié
            $user = $this->getUser();

            // On récupère le profil joueur lié au compte utilisateur
            $joueur = $joueurRepository->findOneByUser($user);

            // On récupère l'equipe de l'utilisateur
            $equipe = $joueur->getEquipe();

            // On récupère la liste des joueurs de l'equipe'
            $joueurs = $equipe->getJoueurs();
        }

        return $this->render('joueur/index.html.twig', [
            'joueurs' =>  $joueurs,
        ]);
    }

    /**
     * @Route("/new", name="joueur_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
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
    public function show(Joueur $joueur): Response
    {
        return $this->render('joueur/show.html.twig', [
            'joueur' => $joueur,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="joueur_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Joueur $joueur): Response
    {
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
        if ($this->isCsrfTokenValid('delete'.$joueur->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($joueur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('joueur_index', [], Response::HTTP_SEE_OTHER);
    }
}
