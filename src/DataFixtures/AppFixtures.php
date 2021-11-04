<?php

namespace App\DataFixtures;

use App\Entity\Equipe;
use App\Entity\Entraineur;
use App\Entity\Championnat;
use App\Entity\Journer;
use App\Entity\Joueur;
use App\Entity\Position;
use App\Entity\Domicile;
use App\Entity\Visiteur;
use App\Entity\Categorie;
use App\Entity\Groupe;
use App\Entity\Section;
use App\Entity\Tag;
use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker\Factory as FakerFactory;



class AppFixtures extends Fixture implements FixtureGroupInterface
{
    private $encoder;
    private $faker;


    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
        $this->faker = FakerFactory::create('fr_FR');

    }

    public static function getGroups(): array
    {
        return ['test'];
    }


    public function load(ObjectManager $manager)
    {      
        // Définition du nombre d'objets qu'il faut créer.

        $groupeCount = 3;
        $joueurParGroupe = 16;
        $joueurParCategorie = $groupeCount * $joueurParGroupe;
        $nbcategories = 7;
        $joueurCount = $joueurParCategorie * $nbcategories;

        $this->loadAdmins($manager);
        // $journers = $this->loadJourners($manager);
        $postAndTag = $this->loadPostsAndTags($manager);

        // $equipes = $this->loadEquipes($manager, $equipeCount);
        $groupes = $this->loadGroupes($manager);
        $sections = $this->loadSections($manager);
        $positions = $this->loadPositions($manager);
        $categories = $this->loadCategories($manager,$groupes, $sections);
        $joueurs = $this->loadJoueurs($manager, $sections, $groupes, $categories, $joueurParCategorie, $positions);
        $entraineurs = $this->loadEntraineurs($manager, $categories, $groupes, $sections );
        // $visiteurs = $this->loadVisiteurs($manager, $equipes);
        // $domiciles = $this->loadDomiciles($manager, $equipes, $visiteurs);
        $manager->flush();
    }

    public function loadAdmins(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('admin@example.com');
        // Hachage du mot de passe.
        $password = $this->encoder->encodePassword($user, '123');
        $user->setPassword($password);
        $user->setRoles(['ROLE_ADMIN']);

        // Demande d'enregistrement d'un objet dans la BDD.
        $manager->persist($user);
    }


    public function loadPositions($manager)
    {
        $positions = [];
        $arrayPosition = [['name' => 'AT' ],['name' => 'AG' ],['name' => 'AD' ],['name' => 'MO' ],
        ['name' => 'MG' ],['name' => 'MD' ],['name' => 'MC' ],['name' => 'MDC' ],['name' => 'DD' ],
        ['name' => 'DC' ],['name' => 'DG' ],['name' => 'GB' ],];

        foreach ($arrayPosition as $row) {
            $position = new Position;
            $position->setName($row['name']);
            $manager->persist($position);
            $positions[] = $position;
        }
        return $positions;
    }

    public function loadSections(ObjectManager $manager)
    {
        $sections = [];
        $section = new Section;
        $section->setName('Male');
        $manager->persist($section);
        $sections[]=$section;

        $section = new Section;
        $section->setName('Female');
        $manager->persist($section);

        // foreach ($categories as $categorie) {
        //     $section->addCategories($categories);
        // }

        $sections[]=$section;

        return $sections;
        

    }
    public function loadCategories(ObjectManager $manager, array $groupes, array $sections)
    {

        $arrayCategoires=[
            ["nom"=>"U8-U9"], ["nom"=>"U10-U11"],["nom"=>"U12-U13"],["nom"=>"U14-U15"],["nom"=>"U16-U17"],["nom"=>"Senior"],["nom"=>"Veteran"],
    ];
            
        $categories = [];
        foreach ($arrayCategoires as $row) {
            $categorie = new Categorie;
            $categorie->setNom($row['nom']);
            foreach($groupes as $groupe)
            {
                $categorie->addGroupe($groupe);
            }
            foreach($sections as $section)
            {
                $categorie->addSection($section);
            }

            $manager->persist($categorie);
            $categories[] = $categorie;

        }

        return $categories;
    }

    public function loadGroupes(ObjectManager $manager)
    {
        $groupes =[];
        $groupe = new Groupe();
        $groupe->setName('A');
        $groupes[] = $groupe;
        $manager->persist($groupe);

        $groupe = new Groupe();
        $groupe->setName('B');
        $manager->persist($groupe);
        $groupes[] = $groupe;

        $groupe = new Groupe();
        $groupe->setName('C');
        $manager->persist($groupe);

        $groupes[] = $groupe;

        return $groupes;

    }

    public function loadEntraineurs($manager, array $categories, array $groupes, array $sections)
    {
        // le nombre d'entraineur depend du nombre de categories, groupes et sections 
        $entraineurCount = count($categories) * count($groupes) * count($sections);
        $entraineurs = [];
        $entraineur = new Entraineur;
        $entraineur->setNom('test');
        $entraineur->setPrenom('entraineur');
        $entraineur->setCategories($categories[6]);
        $entraineur->setGroupe($groupes[0]);
        $entraineur->addSection($sections[0]);
        $manager->persist($entraineur);
        $entraineurs[]=$entraineur;

        for ($i=1; $i <=$entraineurCount ; $i++) { 
       
        $entraineur = new Entraineur;
        $entraineur->setNom($this->faker->firstName());
        $entraineur->setPrenom($this->faker->lastName());
        $entraineur->setCategories($categories[random_int(0,6)]);
        $entraineur->setGroupe($groupes[random_int(0,2)]);
        $entraineur->addSection($sections[random_int(0,1)]);
        $manager->persist($entraineur);
        $entraineurs[]=$entraineur;
        }
        return $entraineurs;
    }

    public function loadJoueurs(ObjectManager $manager, array $sections, array $groupes, array $categories, int $joueurParCategorie, array $positions )
    {
        $equipes = [];
        $equipe = new Equipe();
        $equipe->setName("tilloy"); 
        $manager->persist($equipe);
        $equipes[] = $equipe;
        $tilloy = $equipes[0];
        $maleIndex = 0;
        $femaleIndex = 0;

        $groupeIndex = 0;
        $groupe = $groupes[$groupeIndex];

        $male = $sections[$maleIndex];
        $female = $sections[1];


        // $user = new User();
        // $user->setEmail('joueur@example.com');
        // // Hachage du mot de passe.
        // $password = $this->encoder->encodePassword($user, '123');
        // $user->setPassword($password);
        // // Le format de la chaîne de caractères ROLE_FOO_BAR_BAZ
        // // est libre mais il vaut mieux suivre la convention
        // // proposée par Symfony.
        // $user->setRoles(['ROLE_JOUEUR']);
        // // $manager->persist($user);

        $joueur = new Joueur;
        $joueur -> setSexe('M');
        $joueur -> setNom('joueur');
        $joueur -> setPrenom('joueur');
        $joueur->setEquipe($tilloy);
        $joueur->setSection($male);
        $joueur->setGroupe($groupe);
        $joueur->setCategorie($categories[5]);

        // $joueur->setUser($user);
        $joueur->setPosition($positions[0]);
        $joueur -> setDateDeNaissance($this->faker->dateTimeBetween($startDate = '-50 years', $endDate = 'now'));
        $manager->persist($joueur);

        $joueurs[]=$joueur;

        $equipes = [];
        $equipe = new Equipe();
        $equipe->setName("tilloy"); 
        $manager->persist($equipe);
        $equipes[] = $equipe;
        $tilloy = $equipes[0];
        $maleIndex = 0;
        $femaleIndex = 0;

        $groupeIndex = 0;
        $groupe = $groupes[$groupeIndex];

        $male = $sections[$maleIndex];
        $female = $sections[1];


        // $user = new User();
        // $user->setEmail('joueuse@example.com');
        // // Hachage du mot de passe.
        // $password = $this->encoder->encodePassword($user, '123');
        // $user->setPassword($password);
        // // Le format de la chaîne de caractères ROLE_FOO_BAR_BAZ
        // // est libre mais il vaut mieux suivre la convention
        // // proposée par Symfony.
        // $user->setRoles(['ROLE_JOUEUR']);
        // // $manager->persist($user);

        $joueur = new Joueur;
        $joueur -> setSexe('F');
        $joueur -> setNom('joueuse');
        $joueur -> setPrenom('joueuse');
        $joueur->setEquipe($tilloy);
        $joueur->setSection($female);
        $joueur->setGroupe($groupe);
        $joueur->setCategorie($categories[5]);
        // $joueur->setUser($user);
        $joueur->setPosition($positions[0]);
        $joueur -> setDateDeNaissance($this->faker->dateTimeBetween($startDate = '-50 years', $endDate = 'now'));
        $manager->persist($joueur);

        $joueurs[]=$joueur;

        for ($i=1; $i <$joueurParCategorie; $i++) { 
            // $user = new User();
            // $user->setEmail($this->faker->email());
            // $password = $this->encoder->encodePassword($user, '123');
            // $user->setPassword($password);
            // $user->setRoles(['ROLE_JOUEUR']);
            // // $manager->persist($user);
            $positionIndex = 0;
            $joueur = new Joueur;
            $joueur -> setSexe('M');
            $joueur -> setNom($this->faker->firstNameMale ());
            $joueur -> setPrenom($this->faker->lastName());
            $joueur->setEquipe($tilloy);
            $joueur->setSection($male);
            $joueur->setGroupe($groupes[random_int(0, 2)]);
            $joueur->setPosition($positions[random_int(0, 11)]);
            $joueur -> setCartonJaune(random_int(0,10));
            $joueur -> setCartonRouge(random_int(0,10));
            $joueur -> setBut(random_int(0,10));
            $joueur -> setMatchJouer(random_int(0,10));
            $joueur->setDateDeNaissance($this->faker->dateTimeBetween($startDate = '-9 years', $endDate = 'now -8 years'));
            $joueur->setCategorie($categories[0]);
            // $joueur->setUser($user);
            $positionIndex = 0;
    
            $manager->persist($joueur);

            $joueurs[] = $joueur;
        }
        for ($i=1; $i <$joueurParCategorie; $i++) { 
            // $user = new User();
            // $user->setEmail($this->faker->email());
            // $password = $this->encoder->encodePassword($user, '123');
            // $user->setPassword($password);
            // $user->setRoles(['ROLE_JOUEUR']);
            // $manager->persist($user);
            $joueur = new Joueur;
            $joueur -> setSexe('M');
            $joueur -> setNom($this->faker->firstNameMale ());
            $joueur -> setPrenom($this->faker->lastName());
            $joueur->setEquipe($tilloy);
            $joueur->setSection($male);
            $joueur->setGroupe($groupes[random_int(0, 2)]);
            $joueur -> setCartonJaune(random_int(0,10));
            $joueur -> setCartonRouge(random_int(0,10));
            $joueur -> setBut(random_int(0,10));
            $joueur -> setMatchJouer(random_int(0,10));
            $joueur->setPosition($positions[random_int(0, 11)]);
            $joueur->setDateDeNaissance($this->faker->dateTimeBetween($startDate = '-11 years', $endDate = 'now -10 years'));
            $joueur->setCategorie($categories[1]);
            // $joueur->setUser($user);
            foreach ($positions as $position) {
                $joueur->setPosition($position);
            }
    
            $manager->persist($joueur);

            $joueurs[] = $joueur;
        }
        for ($i=1; $i <$joueurParCategorie; $i++) { 
            // $user = new User();
            // $user->setEmail($this->faker->email());
            // $password = $this->encoder->encodePassword($user, '123');
            // $user->setPassword($password);
            // $user->setRoles(['ROLE_JOUEUR']);
            // $manager->persist($user);
            $male = $sections[0];
            $female = $sections[1];
            $joueur = new Joueur;
            $joueur -> setSexe('M');
            $joueur -> setNom($this->faker->firstNameMale ());
            $joueur -> setPrenom($this->faker->lastName());
            $joueur->setEquipe($tilloy);
            $joueur->setSection($male);
            $joueur->setGroupe($groupes[random_int(0, 2)]);
            $joueur -> setCartonJaune(random_int(0,10));
            $joueur -> setCartonRouge(random_int(0,10));
            $joueur -> setBut(random_int(0,10));
            $joueur -> setMatchJouer(random_int(0,10));
            $joueur->setPosition($positions[random_int(0, 11)]);
            $joueur->setDateDeNaissance($this->faker->dateTimeBetween($startDate = '-13 years', $endDate = 'now -12 years'));
            $joueur->setCategorie($categories[2]);
            // $joueur->setUser($user);
            foreach ($positions as $position) {
                $joueur->setPosition($position);
            }
    
            $manager->persist($joueur);

            $joueurs[] = $joueur;
        }
        for ($i=1; $i <$joueurParCategorie; $i++) { 
            // $user = new User();
            // $user->setEmail($this->faker->email());
            // $password = $this->encoder->encodePassword($user, '123');
            // $user->setPassword($password);
            // $user->setRoles(['ROLE_JOUEUR']);

            // $manager->persist($user);
            $joueur = new Joueur;
            $joueur -> setSexe('M');
            $joueur -> setNom($this->faker->firstNameMale ());
            $joueur -> setPrenom($this->faker->lastName());
            $joueur->setEquipe($tilloy);
            $joueur->setSection($male);
            $joueur->setGroupe($groupes[random_int(0, 2)]);
            $joueur -> setCartonJaune(random_int(0,10));
            $joueur -> setCartonRouge(random_int(0,10));
            $joueur -> setBut(random_int(0,10));
            $joueur -> setMatchJouer(random_int(0,10));
            $joueur->setPosition($positions[random_int(0, 11)]);
            $joueur->setDateDeNaissance($this->faker->dateTimeBetween($startDate = '-15 years', $endDate = 'now -14 years'));
            $joueur->setCategorie($categories[3]);
            // $joueur->setUser($user);
            foreach ($positions as $position) {
                $joueur->setPosition($position);
            }
    
            $manager->persist($joueur);

            $joueurs[] = $joueur;
        }
        for ($i=1; $i <$joueurParCategorie; $i++) { 
            // $user = new User();
            // $user->setEmail($this->faker->email());
            // $password = $this->encoder->encodePassword($user, '123');
            // $user->setPassword($password);
            // $user->setRoles(['ROLE_JOUEUR']);
            // $manager->persist($user);
            $joueur = new Joueur;
            $joueur -> setSexe('M');
            $joueur -> setNom($this->faker->firstNameMale ());
            $joueur -> setPrenom($this->faker->lastName());
            $joueur->setEquipe($tilloy);
            $joueur->setSection($male);
            $joueur->setGroupe($groupes[random_int(0, 2)]);
            $joueur -> setCartonJaune(random_int(0,10));
            $joueur -> setCartonRouge(random_int(0,10));
            $joueur -> setBut(random_int(0,10));
            $joueur -> setMatchJouer(random_int(0,10));
            $joueur->setPosition($positions[random_int(0, 11)]);
            $joueur->setDateDeNaissance($this->faker->dateTimeBetween($startDate = '-17 years', $endDate = 'now -16 years'));
            $joueur->setCategorie($categories[4]);
            // $joueur->setUser($user);
            foreach ($positions as $position) {
                $joueur->setPosition($position);
            }
    
            $manager->persist($joueur);

            $joueurs[] = $joueur;
        }
        for ($i=1; $i <$joueurParCategorie; $i++) { 
            // $user = new User();
            // $user->setEmail($this->faker->email());
            // $password = $this->encoder->encodePassword($user, '123');
            // $user->setPassword($password);
            // $user->setRoles(['ROLE_JOUEUR']);
            // $manager->persist($user);
            $joueur = new Joueur;
            $joueur -> setSexe('M');
            $joueur -> setNom($this->faker->firstNameMale ());
            $joueur -> setPrenom($this->faker->lastName());
            $joueur->setEquipe($tilloy);
            $joueur->setSection($male);
            $joueur->setGroupe($groupes[random_int(0, 2)]);
            $joueur -> setCartonJaune(random_int(0,10));
            $joueur -> setCartonRouge(random_int(0,10));
            $joueur -> setBut(random_int(0,10));
            $joueur -> setMatchJouer(random_int(0,10));
            $joueur->setPosition($positions[random_int(0, 11)]);
            $joueur->setDateDeNaissance($this->faker->dateTimeBetween($startDate = '-35 years', $endDate = 'now -18 years'));
            $joueur->setCategorie($categories[5]);
            // $joueur->setUser($user);
            foreach ($positions as $position) {
                $joueur->setPosition($position);
            }
    
            $manager->persist($joueur);

            $joueurs[] = $joueur;
        }

        for ($i=1; $i <$joueurParCategorie; $i++) { 
            // $user = new User();
            // $user->setEmail($this->faker->email());
            // // Hachage du mot de passe.
            // $password = $this->encoder->encodePassword($user, '123');
            // $user->setPassword($password);
            // // Le format de la chaîne de caractères ROLE_FOO_BAR_BAZ
            // // est libre mais il vaut mieux suivre la convention
            // // proposée par Symfony.
            // $user->setRoles(['ROLE_JOUEUR']);

            // Demande d'enregistrement d'un objet dans la BDD
            // $manager->persist($user);
            // Création d'une liste aléatoire de projects.
            // Cette liste contient exactement le nombre de projects
            // précisé par $projectsCount.

            // Association d'un teacher et de plusieurs projects.

            // $male = $sections[0];
            // $female = $sections[1];
            $joueur = new Joueur;
            $joueur -> setSexe('M');
            $joueur -> setNom($this->faker->firstNameMale ());
            $joueur -> setPrenom($this->faker->lastName());
            $joueur->setEquipe($tilloy);
            $joueur->setSection($male);
            $joueur->setGroupe($groupes[random_int(0, 2)]);
            $joueur -> setCartonJaune(random_int(0,10));
            $joueur -> setCartonRouge(random_int(0,10));
            $joueur -> setBut(random_int(0,10));
            $joueur -> setMatchJouer(random_int(0,10));
            $joueur->setPosition($positions[random_int(0, 11)]);
            $joueur->setDateDeNaissance($this->faker->dateTimeBetween($startDate = '-50 years', $endDate = 'now -35 years'));
            $joueur->setCategorie($categories[6]);
            // $joueur->setUser($user);
            foreach ($positions as $position) {
                $joueur->setPosition($position);
            }
    
            $manager->persist($joueur);

            $joueurs[] = $joueur;
        }
        for ($i=1; $i <$joueurParCategorie; $i++) { 
            // $user = new User();
            // $user->setEmail($this->faker->email());
            // $password = $this->encoder->encodePassword($user, '123');
            // $user->setPassword($password);
            // $user->setRoles(['ROLE_JOUEUR']);
            // $manager->persist($user);
            $joueur = new Joueur;
            $joueur -> setSexe('F');
            $joueur -> setNom($this->faker->firstNameFemale ());
            $joueur -> setPrenom($this->faker->lastName());
            $joueur->setEquipe($tilloy);
            $joueur->setSection($female);
            $joueur->setGroupe($groupes[random_int(0, 2)]);
            $joueur -> setCartonJaune(random_int(0,10));
            $joueur -> setCartonRouge(random_int(0,10));
            $joueur -> setBut(random_int(0,10));
            $joueur -> setMatchJouer(random_int(0,10));
            $joueur->setPosition($positions[random_int(0, 11)]);
            $joueur->setDateDeNaissance($this->faker->dateTimeBetween($startDate = '-9 years', $endDate = 'now -8 years'));
            $joueur->setCategorie($categories[0]);
            // $joueur->setUser($user);
            foreach ($positions as $position) {
                $joueur->setPosition($position);
            }
    
            $manager->persist($joueur);

            $joueurs[] = $joueur;
        }
        for ($i=1; $i <$joueurParCategorie; $i++) { 
            // $user = new User();
            // $user->setEmail($this->faker->email());
            // $password = $this->encoder->encodePassword($user, '123');
            // $user->setPassword($password);
            // $user->setRoles(['ROLE_JOUEUR']);
            // // $manager->persist($user);
            $joueur = new Joueur;
            $joueur -> setSexe('F');
            $joueur -> setNom($this->faker->firstNameFemale ());
            $joueur -> setPrenom($this->faker->lastName());
            $joueur->setEquipe($tilloy);
            $joueur->setSection($female);
            $joueur->setGroupe($groupes[random_int(0, 2)]);
            $joueur -> setCartonJaune(random_int(0,10));
            $joueur -> setCartonRouge(random_int(0,10));
            $joueur -> setBut(random_int(0,10));
            $joueur -> setMatchJouer(random_int(0,10));
            $joueur->setPosition($positions[random_int(0, 11)]);
            $joueur->setDateDeNaissance($this->faker->dateTimeBetween($startDate = '-11 years', $endDate = 'now -10 years'));
            $joueur->setCategorie($categories[1]);
            // $joueur->setUser($user);
            foreach ($positions as $position) {
                $joueur->setPosition($position);
            }
    
            $manager->persist($joueur);

            $joueurs[] = $joueur;
        }
        for ($i=1; $i <$joueurParCategorie; $i++) { 
            // $user = new User();
            // $user->setEmail($this->faker->email());
            // $password = $this->encoder->encodePassword($user, '123');
            // $user->setPassword($password);
            // $user->setRoles(['ROLE_JOUEUR']);
            // $manager->persist($user);
            $joueur = new Joueur;
            $joueur -> setSexe('F');
            $joueur -> setNom($this->faker->firstNameFemale ());
            $joueur -> setPrenom($this->faker->lastName());
            $joueur->setEquipe($tilloy);
            $joueur->setSection($female);
            $joueur->setGroupe($groupes[random_int(0, 2)]);
            $joueur -> setCartonJaune(random_int(0,10));
            $joueur -> setCartonRouge(random_int(0,10));
            $joueur -> setBut(random_int(0,10));
            $joueur -> setMatchJouer(random_int(0,10));
            $joueur->setPosition($positions[random_int(0, 11)]);
            $joueur->setDateDeNaissance($this->faker->dateTimeBetween($startDate = '-13 years', $endDate = 'now -12 years'));
            $joueur->setCategorie($categories[2]);
            // $joueur->setUser($user);
            foreach ($positions as $position) {
                $joueur->setPosition($position);
            }
    
            $manager->persist($joueur);

            $joueurs[] = $joueur;
        }
        for ($i=1; $i <$joueurParCategorie; $i++) { 
            // $user = new User();
            // $user->setEmail($this->faker->email());
            // $password = $this->encoder->encodePassword($user, '123');
            // $user->setPassword($password);
            // $user->setRoles(['ROLE_JOUEUR']);
            // $manager->persist($user);
            $joueur = new Joueur;
            $joueur -> setSexe('F');
            $joueur -> setNom($this->faker->firstNameFemale ());
            $joueur -> setPrenom($this->faker->lastName());
            $joueur->setEquipe($tilloy);
            $joueur->setSection($female);
            $joueur->setGroupe($groupes[random_int(0, 2)]);
            $joueur -> setCartonJaune(random_int(0,10));
            $joueur -> setCartonRouge(random_int(0,10));
            $joueur -> setBut(random_int(0,10));
            $joueur -> setMatchJouer(random_int(0,10));
            $joueur->setPosition($positions[random_int(0, 11)]);
            $joueur->setDateDeNaissance($this->faker->dateTimeBetween($startDate = '-15 years', $endDate = 'now -14 years'));
            $joueur->setCategorie($categories[3]);
            // $joueur->setUser($user);
            foreach ($positions as $position) {
                $joueur->setPosition($position);
            }
    
            $manager->persist($joueur);

            $joueurs[] = $joueur;
        }
        for ($i=1; $i <$joueurParCategorie; $i++) { 
            // $user = new User();
            // $user->setEmail($this->faker->email());
            // $password = $this->encoder->encodePassword($user, '123');
            // $user->setPassword($password);
            // $user->setRoles(['ROLE_JOUEUR']);
            // $manager->persist($user);
            $joueur = new Joueur;
            $joueur -> setSexe('F');
            $joueur -> setNom($this->faker->firstNameFemale ());
            $joueur -> setPrenom($this->faker->lastName());
            $joueur->setEquipe($tilloy);
            $joueur->setSection($female);
            $joueur->setGroupe($groupes[random_int(0, 2)]);
            $joueur -> setCartonJaune(random_int(0,10));
            $joueur -> setCartonRouge(random_int(0,10));
            $joueur -> setBut(random_int(0,10));
            $joueur -> setMatchJouer(random_int(0,10));
            $joueur->setPosition($positions[random_int(0, 11)]);
            $joueur->setDateDeNaissance($this->faker->dateTimeBetween($startDate = '-17 years', $endDate = 'now -16 years'));
            $joueur->setCategorie($categories[4]);
            // $joueur->setUser($user);
            foreach ($positions as $position) {
                $joueur->setPosition($position);
            }
    
            $manager->persist($joueur);

            $joueurs[] = $joueur;
        }
        for ($i=1; $i <$joueurParCategorie; $i++) { 
            // $user = new User();
            // $user->setEmail($this->faker->email());
            // $password = $this->encoder->encodePassword($user, '123');
            // $user->setPassword($password);
            // $user->setRoles(['ROLE_JOUEUR']);
            // $manager->persist($user);
            $joueur = new Joueur;
            $joueur -> setSexe('F');
            $joueur -> setNom($this->faker->firstNameFemale ());
            $joueur -> setPrenom($this->faker->lastName());
            $joueur->setEquipe($tilloy);
            $joueur->setSection($female);
            $joueur->setGroupe($groupes[random_int(0, 2)]);
            $joueur -> setCartonJaune(random_int(0,10));
            $joueur -> setCartonRouge(random_int(0,10));
            $joueur -> setBut(random_int(0,10));
            $joueur -> setMatchJouer(random_int(0,10));
            $joueur->setDateDeNaissance($this->faker->dateTimeBetween($startDate = '-35 years', $endDate = 'now -18 years'));
            $joueur->setCategorie($categories[5]);
            // $joueur->setUser($user);
            $joueur->setPosition($positions[random_int(0, 11)]);
            $manager->persist($joueur);

            $joueurs[] = $joueur;
        }

        for ($i=1; $i <$joueurParCategorie; $i++) { 
            // $user = new User();
            // $user->setEmail($this->faker->email());
            // $password = $this->encoder->encodePassword($user, '123');
            // $user->setPassword($password);
            // $user->setRoles(['ROLE_JOUEUR']);
            // $manager->persist($user);
            $joueur = new Joueur;
            $joueur -> setSexe('F');
            $joueur -> setNom($this->faker->firstNameFemale ());
            $joueur -> setPrenom($this->faker->lastName());
            $joueur->setEquipe($tilloy);
            $joueur->setSection($female);
            $joueur->setGroupe($groupes[random_int(0, 2)]);
            $joueur -> setCartonJaune(random_int(0,10));
            $joueur -> setCartonRouge(random_int(0,10));
            $joueur -> setBut(random_int(0,10));
            $joueur -> setMatchJouer(random_int(0,10));
            $joueur->setDateDeNaissance($this->faker->dateTimeBetween($startDate = '-50 years', $endDate = 'now -35 years'));
            $joueur->setCategorie($categories[6]);
            // $joueur->setUser($user);
            $joueur->setPosition($positions[random_int(0, 11)]);
    
            $manager->persist($joueur);

            $joueurs[] = $joueur;
        }
        return $joueurs;

          
    }

    // public function loadJourners(ObjectManager $manager)
    // {
    //     $journers = [];
    //     $array=[
    //         ["date"=>"2021-09-12 15:00:00"], 
    //         ["date"=>"2021-09-26 15:00:00"], 
    //         ["date"=>"2021-10-10 15:00:00"], 
    //         ["date"=>"2021-10-24 15:00:00"], 
    //         ["date"=>"2021-11-07 15:00:00"], 
    //         ["date"=>"2021-11-14 15:00:00"], 
    //         ["date"=>"2021-11-21 15:00:00"], 
    //         ["date"=>"2021-12-05 15:00:00"], 
    //         ["date"=>"2021-12-12 15:00:00"], 
    //         ["date"=>"2022-02-20 15:00:00"], 
    //         ["date"=>"2022-02-27 15:00:00"], 
    //         ["date"=>"2022-03-06 15:00:00"], 
    //         ["date"=>"2022-03-13 15:00:00"], 
    //         ["date"=>"2022-03-20 15:00:00"], 
    //         ["date"=>"2022-03-27 15:00:00"], 
    //         ["date"=>"2022-04-03 15:00:00"], 
    //         ["date"=>"2022-04-10 15:00:00"], 
    //         ["date"=>"2022-04-24 15:00:00"], 
    //         ["date"=>"2022-05-08 15:00:00"],
    //         ["date"=>"2022-05-15 15:00:00"], 
    //         ["date"=>"2022-05-22 15:00:00"], 
    //         ["date"=>"2022-05-29 15:00:00"]
    
    //         ];
    //         foreach($array as $row)
    //         {
    //                 $journer = new Journer();
    //                 $journer->setDate(\DateTime::createFromFormat('Y-m-d H:i:s',$row["date"]));
    //                 $manager->persist($journer);
    //                 $journers[] = $journer;
    //         }
    //         return $journers;

    // }


    // public function loadEquipes(ObjectManager $manager)
    // {
    //     $equipes = [];
    //         $equipe = new Equipe();
    //         $equipe->setName("tilloy"); 
    //         for ($i=1; $i <=12 ; $i++) { 
    //             $equipe = new Equipe();
    //             $equipe->setName($this->faker->name()); 

    //         }
    //         $manager->persist($equipe);
    //         $equipes[] = $equipe;


        
    //     return $equipes;
    // }
   
    // public function loadVisiteurs(ObjectManager $manager, array $equipes)
    // {
    //     $matchJouer = 1;
    //     $score = 1;

    //     $visiteurs = [];
    //     $equipeIndexTilloy = 0;
    //     $tilloy = $equipes[$equipeIndexTilloy];

    //         $visiteur = new Visiteur();
    //         $visiteur->setScore($score);
    //         $visiteur->setEquipe($tilloy);
    //         $visiteur->getEquipe()->setga($matchJouer);
    //         $visiteur->getEquipe()->setbp($score);

    //         $manager->persist($visiteur);
    //         $visiteurs[] = $visiteur;
         
    //     return $visiteurs;


    // }


    // public function loadDomiciles(ObjectManager $manager, array $equipes, array $visiteurs)
    // {
    //     $domiciles = [];
    //     $equipeIndex = 8;
    //     $equipe = $equipes[$equipeIndex];
    //     $visiteurIndex = 0;
    //     $visiteur = $visiteurs[$visiteurIndex];
    //     $ptsMatchGagne = 3;
    //     $ptsMatchNul = 1;
    //     $ga = 1;
    //     $matchJouer = 1;
    //     $bp = 4;
    //     $bc = $visiteur->getEquipe()->getbp();
        

        
    //     $domicile =  new Domicile();
    //     $domicile->setScore($bp);
    //     $domicile->setEquipe($equipe);

    //     $domicile->getEquipe()->setJo($matchJouer);
    //     $visiteur->getEquipe()->setJo($matchJouer);

    //     $domicile->getEquipe()->setGa($ga);

    //     $domicile->getEquipe()->setbp($bp);
    //     $visiteur->getEquipe()->setbc($bp);
        
    //     $domicile->getEquipe()->setBc($bc);

    //     $domicile->getEquipe()->setDiff($bp-$bc);
    //     $visiteur->getEquipe()->setDiff($bc-$bp);

    //     $domicile->setVisiteur($visiteur);
    //     $manager->persist($domicile);
    //     $domiciles[] = $domicile;


    //     if ($visiteur->getScore($visiteur) < $domicile->getScore($domicile)) {
            
    //         $pts = $domicile->getEquipe()->getPts();
    //         $ga = $domicile->getEquipe()->getGa();
    //         $bp = $visiteur->getScore($visiteur);
    //        return $domicile->getEquipe()->setPts($pts+$ptsMatchGagne); 

    //     }
    //     elseif ($visiteur->getScore($visiteur) > $domicile->getScore($domicile)) {
            
    //         $pts = $visiteur->getEquipe()->getPts();
    //        return $visiteur->getEquipe()->setPts($pts+$ptsMatchGagne); 
    //     }  

    //     elseif ($visiteur->getScore($visiteur) == $domicile->getScore($domicile)) {
            
    //         $ptsVisiteur = $visiteur->getEquipe()->getPts();
    //         $ptsDomicile = $domicile->getEquipe()->getPts();
    //         return $domicile->getEquipe()->setPts($ptsDomicile+$ptsMatchNul) && $visiteur->getEquipe()->setPts($ptsVisiteur+$ptsMatchNul) ; 
            
    //             }  

    //     return $domiciles;

    // }
    public function loadPostsAndTags(ObjectManager $manager)
    {        
        


        for ($i = 1; $i <= 20; $i++) {
            $tag = new Tag();
            $tag->setName($this->faker->words($nb = 3, $asText = true));
            $manager->persist($tag);
        }

        $manager->flush();


        for ($i = 1; $i <= 20; $i++) {
            $post = new Post();
            $post->setTitre($this->faker->sentence($nbWords = 6, $variableNbWords = true));
            $post->setBody($this->faker->text($maxNbChars = 1000));
            $post->setPublishDate($this->faker->dateTimeBetween($startDate = '-1 year', $endDate = 'now', $timezone = null));
            $post->addTag($tag);
            $manager->persist($post);
        }

        $manager->flush();
    }

}
