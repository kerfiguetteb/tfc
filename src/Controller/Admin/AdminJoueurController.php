<?php

namespace App\Controller\Admin;

use App\Entity\Joueur;
use App\Entity\User;
use App\Form\JoueurType;
use App\Repository\JoueurRepository;
use App\Repository\EntraineurRepository;
use App\Repository\EquipeRepository;
use App\Repository\CategorieRepository;
use App\Service\JoueurSearchFormViewFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use App\Controller\AlgoCategorieTrait;



/**
 * @Route("admin/joueur")
 */
class AdminJoueurController extends AbstractController
{
    use AlgoCategorieTrait;

    /**
     * @Route("/", name="admin_joueur_index", methods={"GET"})
     */
    public function index(JoueurRepository $joueurRepository,JoueurSearchFormViewFactory $joueurSearchFormViewFactory, Request $request): Response
    {
        $joueurs = $joueurRepository->findAll();

        return $this->render('admin/joueur/index.html.twig', [
            'joueurs' => $joueurs,
        ]);
    }

    /**
     * @Route("/new", name="admin_joueur_new", methods={"GET","POST"})
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

            return $this->redirectToRoute('admin_joueur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/joueur/new.html.twig', [
            'joueur' => $joueur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_joueur_show", methods={"GET"})
     */
    public function show(Joueur $joueur, JoueurRepository $joueurRepository): Response
    {

        return $this->render('admin/joueur/show.html.twig', [
            'joueur' => $joueur,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_joueur_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Joueur $joueur, JoueurRepository $joueurRepository,
    EquipeRepository $equipeRepository,
    CategorieRepository $categorieRepository
): Response
    {
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

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_joueur_index', [], Response::HTTP_SEE_OTHER);

        }

        return $this->render('admin/joueur/edit.html.twig', [
            'joueur' => $joueur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_joueur_delete", methods={"POST"})
     */
    public function delete(Request $request, Joueur $joueur): Response
    {
        if ($this->isCsrfTokenValid('delete'.$joueur->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($joueur);
            $entityManager->flush();
        }
        return $this->redirectToRoute('admin_joueur_index', [], Response::HTTP_SEE_OTHER);


    }

}
