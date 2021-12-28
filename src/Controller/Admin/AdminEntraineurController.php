<?php

namespace App\Controller\Admin;

use App\Entity\Entraineur;
use App\Form\EntraineurType;
use App\Repository\EntraineurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


/**
 * @Route("admin/entraineur")
 */
class AdminEntraineurController extends AbstractController
{
    /**
     * @Route("/", name="admin_entraineur_index", methods={"GET"})
     */
    public function index(EntraineurRepository $entraineurRepository): Response
    {
        return $this->render('admin/entraineur/index.html.twig', [
            'entraineurs' => $entraineurRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin_entraineur_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $entraineur = new Entraineur();
        $form = $this->createForm(EntraineurType::class, $entraineur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $entraineur->getUser();

            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('user')->get('plainPassword')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($entraineur);
            $entityManager->flush();

            return $this->redirectToRoute('admin_entraineur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/entraineur/new.html.twig', [
            'entraineur' => $entraineur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_entraineur_show", methods={"GET"})
     */
    public function show(Entraineur $entraineur): Response
    {
        return $this->render('admin/entraineur/show.html.twig', [
            'entraineur' => $entraineur,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_entraineur_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Entraineur $entraineur): Response
    {
        $form = $this->createForm(EntraineurType::class, $entraineur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_entraineur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/entraineur/edit.html.twig', [
            'entraineur' => $entraineur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_entraineur_delete", methods={"POST"})
     */
    public function delete(Request $request, Entraineur $entraineur): Response
    {
        if ($this->isCsrfTokenValid('delete'.$entraineur->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($entraineur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_entraineur_index', [], Response::HTTP_SEE_OTHER);
    }
    
    private function redirectJoueur(string $route, Joueur $joueur, JoueurRepository $joueurRepository)
    {
        // On vérifie si l'utilisateur est un Joueur
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


