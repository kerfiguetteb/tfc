<?php

namespace App\Controller;

use App\Entity\Joueur;
use App\Entity\Entraineur;
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
use Knp\Component\Pager\PaginatorInterface;



class ProfilController extends AbstractController
{
    /**
     * @Route("/profil", name="profil")
     */
    public function index(PaginatorInterface $paginator,JoueurRepository $joueurRepository, EntraineurRepository $entraineurRepository, Request $request): Response
    {
        $joueurs = $joueurRepository->findAll();
        $user = $this->getUser();
        $joueur = $joueurRepository->findOneByUser($user);
        $birthday = $joueur->getDateDeNaissance();
        $today = new \DateTime(date('Y-m-d H:i:s'));
        $ageDiff = $birthday->diff($today);
        $age = $ageDiff->format('%y');
        $categorieId = $joueur->getCategorie()->getId();
        $categorie = $joueur->getCategorie();
        $joueurs = $categorie->getJoueurs();
        $joueurActive = $joueurRepository->findOneByUser($user);
        $joueurCount = count($joueurs)-1;
        $entraineur = $entraineurRepository->findByCategorie($categorieId);


        // $pagination = $paginator->paginate(
        //     $joueurs, /* query builder NOT result */
        //     $request->query->getInt('page', 1), /*page number*/
        //     6/*limit per page*/
        // );

        return $this->render('profil/index.html.twig', [
            'joueur' => $joueur,
            'joueurs' => $joueurs,
            'age' => $age,
            'joueurActive'=>$joueurActive,
            'joueurCount' => $joueurCount,
            'entraineur' => $entraineur
        ]);
    }
}
