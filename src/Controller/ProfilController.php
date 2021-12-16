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


class ProfilController extends AbstractController
{
    /**
     * @Route("/profil", name="profil")
     */
    public function index(JoueurRepository $joueurRepository): Response
    {
        $joueurs = $joueurRepository->findAll();
        $user = $this->getUser();
        $joueur = $joueurRepository->findOneByUser($user);
        $birthday = $joueur->getDateDeNaissance();
        $today = new \DateTime(date('Y-m-d H:i:s'));
        $ageDiff = $birthday->diff($today);
        $age = $ageDiff->format('%y');



        // return $this->redirectToRoute('joueur_show', ['id'=>$joueur->getId()], Response::HTTP_SEE_OTHER);


        return $this->render('profil/index.html.twig', [
            'joueur' => $joueur,
            'age' => $age,
        ]);
    }
}
