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
        $sectionCount = 2;
        $nbcategories = 7;
        $joueurParGroupe = 16;
        $joueurParCategorie = $groupeCount * $joueurParGroupe;
        $joueurCount = $joueurParCategorie * $nbcategories;

        $this->loadAdmins($manager);
        // $journers = $this->loadJourners($manager);
        $postAndTag = $this->loadPostsAndTags($manager);

        // $equipes = $this->loadEquipes($manager, $equipeCount);
        $positions = $this->loadPositions($manager);
        $categories = $this->loadCategories($manager,$sectionCount,$groupeCount);
        $joueurs = $this->loadJoueurs($manager, $categories, $joueurParGroupe, $positions);
        $entraineurs = $this->loadEntraineurs($manager, $categories, $categories );
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

    public function loadCategories(ObjectManager $manager, int $sectionCount, int $groupeCount)
    {
        // le nombre de categorie a creer depend du nombre de groupe et de section

        $arrayCategoires=[
            ["nom"=>"U8-U9"], ["nom"=>"U10-U11"],["nom"=>"U12-U13"],["nom"=>"U14-U15"],["nom"=>"U16-U17"],["nom"=>"Senior"],["nom"=>"Veteran"],
        ];

        foreach ($arrayCategoires as $row) {
            for ($i=0; $i <$sectionCount ; $i++) { 
                for ($g=0; $g <$groupeCount ; $g++) { 
                    $categorie = new Categorie;
                    $categorie->setNom($row['nom']);
                    if ($i>=1) {
                        $categorie->setSection('Feminine');
                    }else {
                        $categorie->setSection('Masculine');
                    }
                    if ($g>=1 && $g<2) {
                        $categorie->setGroupe('B');
                    }elseif($g>=2 && $g<3){
                        $categorie->setGroupe('C');
                    }else {
                        $categorie->setGroupe('A');
                    }
                    $manager->persist($categorie);
                    $categories[] = $categorie;
                }

            }
            
        }

            return $categories;
    }

    public function loadJoueurs(ObjectManager $manager, array $categories, int $joueurParGroupe, array $positions )
    {
        $equipes = [];
        $equipe = new Equipe();
        $equipe->setName("tilloy"); 
        $manager->persist($equipe);
        $equipes[] = $equipe;
        $tilloy = $equipes[0];
       
        $user = new User();
        $user->setEmail('joueur@example.com');
        // Hachage du mot de passe.
        $password = $this->encoder->encodePassword($user, '123');
        $user->setPassword($password);
        $user->setRoles(['ROLE_JOUEUR']);
        $manager->persist($user);

        $joueur = new Joueur;
        $joueur -> setSexe('M');
        $joueur -> setNom('joueur');
        $joueur -> setPrenom('joueur');
        $joueur->setUser($user);
        $joueur->setEquipe($tilloy);
        $joueur->setPosition($positions[0]);
        $joueur->setCategorie($categories[25]);
        $joueur -> setDateDeNaissance($this->faker->dateTimeBetween($startDate = '-50 years', $endDate = 'now'));
        $manager->persist($joueur);

        foreach ($categories as $categorie) {
            for ($i=1; $i<= $joueurParGroupe ; $i++) { 
                $user = new User();
                $user->setEmail($this->faker->email());
                // Hachage du mot de passe.
                $password = $this->encoder->encodePassword($user, '123');
                $user->setPassword($password);
                $user->setRoles(['ROLE_JOUEUR']);
                $manager->persist($user);
        
                $joueurs[]=$joueur;
                $joueur = new Joueur;
                $joueur -> setSexe('M');
                $joueur -> setNom($this->faker->firstNameMale());
                $joueur -> setPrenom($this->faker->lastName());
                $joueur->setUser($user);
                $joueur->setEquipe($tilloy);
                $joueur->setPosition($positions[0]);
                $joueur->setCategorie($categorie);
                if ($categorie->getNom()== 'U8-U9') {
                    # code...
                    $joueur -> setDateDeNaissance($this->faker->dateTimeBetween($startDate = '-9 years', $endDate = 'now -8years'));
                }
                elseif ($categorie->getNom()== 'U10-U11') {
                    $joueur -> setDateDeNaissance($this->faker->dateTimeBetween($startDate = '-11 years', $endDate = 'now-10years'));
                } 
                elseif ($categorie->getNom()== 'U12-U13') {
                    $joueur -> setDateDeNaissance($this->faker->dateTimeBetween($startDate = '-13 years', $endDate = 'now-12years'));
                } 
                elseif ($categorie->getNom()== 'U14-U15') {
                    $joueur -> setDateDeNaissance($this->faker->dateTimeBetween($startDate = '-15 years', $endDate = 'now-14years'));
                } 
                elseif ($categorie->getNom()== 'U16-U17') {
                    $joueur -> setDateDeNaissance($this->faker->dateTimeBetween($startDate = '-17 years', $endDate = 'now-16years'));
                } 
                elseif ($categorie->getNom()== 'Senior') {
                    $joueur -> setDateDeNaissance($this->faker->dateTimeBetween($startDate = '-35 years', $endDate = 'now-18years'));
                } else {
                    $joueur -> setDateDeNaissance($this->faker->dateTimeBetween($startDate = '-50 years', $endDate = 'now-35years'));
                }
                if ($categorie->getSection() == 'Feminine') {
                    $joueur -> setSexe('F');
                    $joueur -> setNom($this->faker->firstNameFeMale());

                }
                
                $manager->persist($joueur);
        
                $joueurs[]=$joueur;
    
            }    
    
    
        }

        return $joueurs;

    }

    public function loadEntraineurs($manager, array $categories)
    {
        $entraineurs = [];

        $user = new User();
        $user->setEmail('entraineur@example.com');
        // Hachage du mot de passe.
        $password = $this->encoder->encodePassword($user, '123');
        $user->setPassword($password);
        $user->setRoles(['ROLE_ENTRAINEUR']);
        $manager->persist($user);

        $entraineur = new Entraineur;
        $entraineur->setNom('test');
        $entraineur->setPrenom('entraineur');
        $entraineur->setCategories($categories[20]);
        $entraineur->setUser($user);
        $manager->persist($entraineur);
        $entraineurs[]=$entraineur;

        
        foreach ($categories as $categorie) {
            # code...
            $user = new User();
            $user->setEmail($this->faker->email());
            $password = $this->encoder->encodePassword($user, '123');
            $user->setPassword($password);
            $user->setRoles(['ROLE_ENTRAINEUR']);
            $manager->persist($user);
       
            $entraineur = new Entraineur;
            $entraineur->setNom($this->faker->firstName());
            $entraineur->setPrenom($this->faker->lastName());
            $entraineur->setCategories($categorie);
            $entraineur->setUser($user);
            $manager->persist($entraineur);
            $entraineurs[]=$entraineur;
        
        }
        return $entraineurs;
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
