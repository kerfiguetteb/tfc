<?php

namespace App\Controller;

use App\Entity\Joueur;
use App\Entity\Categorie;
use App\Form\JoueurRegistrationType;
use App\Repository\CategorieRepository;
use App\Repository\EquipeRepository;
use App\Repository\JoueurRepository;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, 
    CategorieRepository $categorieRepository,
    EquipeRepository $equipeRepository,     
    JoueurRepository $joueurRepository,
    UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $joueur = new Joueur();
        $form = $this->createForm(JoueurRegistrationType::class, $joueur);
        $form->handleRequest($request);

        // $categories = $categorieRepository->find(1);
        // $section = $categories->getSection();
        // $groupe = $categories->getGroupe();
        // $nom = $categories->getNom();
        // // pour calculer le nombre de joueur par categorie
        // $joueurs = $joueurRepository->findBySectionGroupeName($section,$groupe, $nom);
        // dump($joueurs);
        // $categories = $categorieRepository->findBySectionGroupeName($section, $groupe, $nom);
        if ($form->isSubmitted() && $form->isValid()) {

            $sexe = $form->getData()->getSexe();
            $birthday = $form->getData()->getDateDeNaissance();
            $today = new \DateTime(date('Y-m-d H:i:s'));
            $age = $birthday->diff($today);
            $equipe = $equipeRepository->find(1);
            $tilloy = $equipe;
            dump($tilloy);

            $u9 = 'U8-U9';
            $u11 = 'U10-U11';
            $u13 = 'U12-U13';
            $u15 = 'U14-U15';
            $u17 = 'U16-U17';
            $senior = 'Senior';
            $veteran = 'Veteran';

          
            if ($sexe === 'H') {
                $section = 'Masculine';
            }elseif ($sexe === 'F') {
                $section = 'Feminine';
            }
            // dump($section);
            
            if($age->format('%y') >= "8" && $age->format('%y') < "10") {
                $nom = $u9;
                dump($nom);            

            }if ($age->format('%y') >= "10" && $age->format('%y') < "12"){
                $nom = $u11;
                dump($nom);            

            }if ($age->format('%y') >= "12" && $age->format('%y') < "14") {
                $nom = $u13;
                dump($nom);            

            }if ($age->format('%y') >= "14" && $age->format('%y') < "17")  {
                $nom = $u15;
                dump($nom);            

            }if ($age->format('%y') >= "16" && $age->format('%y') < "18") {
                $nom = $u17;
                dump($nom);            

            }if ($age->format('%y') >= "18" && $age->format('%y') < "35") {
                $nom = $senior;
                dump($nom);            

            }if ($age->format('%y') > "35") {
                $nom = $veteran;
                dump($veteran);            

            }

            dump($age->format('%y'));

            $groupe = 'A';
            $nbMaxPerGroupe = 25;
            $joueurPerSectionGroupeName = $joueurRepository->findBySectionGroupeName($section,$groupe,$nom);
            $joueurCountPerGroupe = count($joueurPerSectionGroupeName)-1;
            dump($joueurPerSectionGroupeName);

            if ($joueurCountPerGroupe > $nbMaxPerGroupe) {
                $groupe = 'B';
                if ($joueurCountPerGroupe > $nbMaxPerGroupe) {
                    $groupe = 'C';
                }
            } 
            $joueurPerSectionGroupeName = $joueurRepository->findBySectionGroupeName($section,$groupe,$nom);
            dump($joueurPerSectionGroupeName);
            $categorie = $categorieRepository->findBySectionGroupeName($section, $groupe, $nom);
            $categorieId = $categorie[0];

            $joueur->setCategorie($categorieId);
            $joueur->setEquipe($tilloy);
            
            dump($joueur);
            $user = $joueur->getUser();
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('user')->get('plainPassword')->getData()
                )
            );

            $user->setRoles(['ROLE_JOUEUR']);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($joueur);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('joueur_show', [
                'id' => $joueur->getId(),
            ]);
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

 
}
