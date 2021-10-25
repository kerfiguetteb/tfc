<?php

namespace App\DataFixtures;

use App\Entity\Equipe;
use App\Entity\Journer;
use App\Entity\Joueur;
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
use Faker\Factory;



class AppFixtures extends Fixture implements FixtureGroupInterface
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public static function getGroups(): array
    {
        return ['test'];
    }


    public function load(ObjectManager $manager)
    {        
        $this->loadAdmins($manager);
        $postAndTag = $this->loadPostsAndTags($manager);
        $journers = $this->loadJourners($manager);
        $categories = $this->loadCategories($manager);
        $equipes = $this->loadEquipes($manager, $categories);
        $joueurs = $this->loadJoueurs($manager,$equipes);
        $visiteurs = $this->loadVisiteurs($manager, $equipes);
        $domiciles = $this->loadDomiciles($manager, $equipes, $visiteurs);
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

    public function loadJoueurs(ObjectManager $manager, array $equipes)
    {
        $joueurs = [];
        $tilloyIndex= 0;
        $tilloy = $equipes[$tilloyIndex];
        $array = [
            ["nom" => "AJARRAY", "prenom" => "Mohamed", "dateDeNaissance"=>"01/09/1995", "email"=>"mohamedajarray@outlook.com" ],
            ["nom" => "BADAOUI", "prenom" =>"Salim", "dateDeNaissance"=>"19/04/2000","email"=>"badaouisalim.arras@gmail.com"],
            ["nom" => "BADAOUI","prenom"=>"Yassine", "dateDeNaissance"=>"04/04/1998","email"=>"badaouiyassine.arras@gmail.com"],
            ["nom" => "FERAHTIA","prenom"=>"Dalil", "dateDeNaissance"=>"12/10/1990","email"=>"ferahtiadalil@gmail.com"],
            ["nom" => "GODFRIN","prenom"=>"Guillaume", "dateDeNaissance"=>"28/08/1987","email"=>"guillaume.godfrin@sfr.fr"],
            ["nom" => "HASSAINE","prenom"=>"Anthony", "dateDeNaissance"=>"14/10/1992","email"=>"hassaine.anthony@gmail.com"],
            ["nom" => "HAYOUNI","prenom"=>"Mohamed", "dateDeNaissance"=>"17/09/1985","email"=>"moharajaehayouni@yahoo.fr"],
            ["nom" => "JAIFI","prenom"=>"Mehdi", "dateDeNaissance"=>"17/04/1987","email"=>"mehdi.jaifi@gmail.com"],
            ["nom" => "JEANNE","prenom"=>"Xavier", "dateDeNaissance"=>"08/09/1987","email"=>"jeanne.xav@gmail.com"],
            ["nom" => "JEDAINI","prenom"=>"Saadallah", "dateDeNaissance"=>"09/03/1990","email"=>"saadjedaini@gmail.com"],
            ["nom" => "JOLIBOIS","prenom"=>"Tony", "dateDeNaissance"=>"10/06/1997","email"=>"tonyjolibois@hotmail.fr"],
            ["nom" => "KERFI GUETTEB","prenom"=>"Outman", "dateDeNaissance"=>"12/12/1989","email"=>"outman62@live.fr"],
            ["nom" => "LEFORT","prenom"=>"Maxime", "dateDeNaissance"=>"04/10/1988","email"=>"max.lefort@laposte.net"],
            ["nom" => "LOUDADSI","prenom"=>"Abdenabi", "dateDeNaissance"=>"17/02/1981","email"=>"abi.loudadsi@gmail.com"],
            ["nom" => "LOUDADSI","prenom"=>"Issam", "dateDeNaissance"=>"22/04/1988","email"=>"loudadsi.issam@live.fr"],
            ["nom" => "MELIN","prenom"=>"Jean Baptiste", "dateDeNaissance"=>"18/05/1990","email"=>"jbmelin@gmail.com"],
            ["nom" => "MELIN","prenom"=>"Jean Philippe", "dateDeNaissance"=>"02/11/1991","email"=>"jf_fifi@hotmail.fr"],
            ["nom" => "MIGANE","prenom"=>"Hamza", "dateDeNaissance"=>"19/12/1988","email"=>"hamza-m@hotmail.fr"],
            ["nom" => "NICOLLE","prenom"=>"Valentin", "dateDeNaissance"=>"29/02/1992","email"=>"valentin_nicolle@yahoo.fr"],
            ["nom" => "QRITA","prenom"=>"Otman", "dateDeNaissance"=>"15/09/1988","email"=>"qritaotman645@gmail.com"],
            
        ];    

        foreach($array as $row)
        {
            $user = new User();
            $user->setEmail($row['email']);
            $password = $this->encoder->encodePassword($user, '123');
            $user->setPassword($password);
            $user->setRoles(['ROLE_JOUEUR']);
            $manager->persist($user);

            $joueur = new Joueur();
            $joueur->setNom($row["nom"]);
            $joueur->setPrenom($row["prenom"]);
            $joueur->setCartonJaune(0);
            $joueur->setCartonRouge(0);
            $joueur->setSexe('H');
            $joueur->setDateDeNaissance(\DateTime::createFromFormat('d/m/Y', $row['dateDeNaissance']));;
            // $joueur->setEmail($row['email']);
            $joueur->setMatchJouer(0);
            $joueur->setBut(0);
            $joueur->setEquipe($tilloy);
            $joueur->setUser($user);
            $manager->persist($joueur);
            $joueurs[] = $joueur;
        }    
        return $joueurs;
          
    }

    public function loadJourners(ObjectManager $manager)
    {
        $journers = [];
        $array=[
            ["date"=>"2021-09-12 15:00:00"], 
            ["date"=>"2021-09-26 15:00:00"], 
            ["date"=>"2021-10-10 15:00:00"], 
            ["date"=>"2021-10-24 15:00:00"], 
            ["date"=>"2021-11-07 15:00:00"], 
            ["date"=>"2021-11-14 15:00:00"], 
            ["date"=>"2021-11-21 15:00:00"], 
            ["date"=>"2021-12-05 15:00:00"], 
            ["date"=>"2021-12-12 15:00:00"], 
            ["date"=>"2022-02-20 15:00:00"], 
            ["date"=>"2022-02-27 15:00:00"], 
            ["date"=>"2022-03-06 15:00:00"], 
            ["date"=>"2022-03-13 15:00:00"], 
            ["date"=>"2022-03-20 15:00:00"], 
            ["date"=>"2022-03-27 15:00:00"], 
            ["date"=>"2022-04-03 15:00:00"], 
            ["date"=>"2022-04-10 15:00:00"], 
            ["date"=>"2022-04-24 15:00:00"], 
            ["date"=>"2022-05-08 15:00:00"],
            ["date"=>"2022-05-15 15:00:00"], 
            ["date"=>"2022-05-22 15:00:00"], 
            ["date"=>"2022-05-29 15:00:00"]
    
            ];
            foreach($array as $row)
            {
                    $journer = new Journer();
                    $journer->setDate(\DateTime::createFromFormat('Y-m-d H:i:s',$row["date"]));
                    $manager->persist($journer);
                    $journers[] = $journer;
            }
            return $journers;

    }
    public function loadCategories(ObjectManager $manager)
    {
        $categories = [];
        $categorie = new Categorie;
        $categorie->setNom('Senior A');
        $manager->persist($categorie);
        $categories[] = $categorie;

        $categorie = new Categorie;
        $categorie->setNom('Senior B');
        $manager->persist($categorie);
        $categories[] = $categorie;

        $categorie = new Categorie;
        $categorie->setNom('Senior C');
        $manager->persist($categorie);
        $categories[] = $categorie;

        return $categories;
    }

    public function loadEquipes(ObjectManager $manager, array $categories)
    {
        $equipes = [];

        $seniorA = $categories[0];        
        $seniorB = $categories[1];
        
        $array = [
            ["name" => "Tilloy Fc 1","pts"=>0,"jo"=> 0,"ga"=>0,"nu"=>0,"pe"=>0,"bp"=>0,"bc"=>0,'diff'=>0],
            ["name" => "Artois Aj 1","pts"=>0,"jo"=> 0,"ga"=>0,"nu"=>0,"pe"=>0,"bp"=>0,"bc"=>0,'diff'=>0],
            ["name" => "Ecourt Js 1","pts"=>0,"jo"=> 0,"ga"=>0,"nu"=>0,"pe"=>0,"bp"=>0,"bc"=>0,'diff'=>0],
            ["name" => "Brebieres As 1","pts"=>0,"jo"=> 0,"ga"=>0,"nu"=>0,"pe"=>0,"bp"=>0,"bc"=>0,'diff'=>0],
            ["name" => "Camblain Fc 1","pts"=>0,"jo"=> 0,"ga"=>0,"nu"=>0,"pe"=>0,"bp"=>0,"bc"=>0,'diff'=>0],
            ["name" => "Beaumetz Sud Artois 1","pts"=>0,"jo"=> 0,"ga"=>0,"nu"=>0,"pe"=>0,"bp"=>0,"bc"=>0,'diff'=>0],
            ["name" => "Henin O 1","pts"=>0,"jo"=> 0,"ga"=>0,"nu"=>0,"pe"=>0,"bp"=>0,"bc"=>0,'diff'=>0],
            ["name" => "Lens As 1","pts"=>0,"jo"=> 0,"ga"=>0,"nu"=>0,"pe"=>0,"bp"=>0,"bc"=>0,'diff'=>0],
            ["name" => "Pas Us 1","pts"=>0,"jo"=> 0,"ga"=>0,"nu"=>0,"pe"=>0,"bp"=>0,"bc"=>0,'diff'=>0],
            ["name" => "Bap Bert Vaulx As 1","pts"=>0,"jo"=> 0,"ga"=>0,"nu"=>0,"pe"=>0,"bp"=>0,"bc"=>0,'diff'=>0],
        ];
        $array2 = [

            ["name" => "St Nicolas Sc 2","pts"=>0,"jo"=> 0,"ga"=>0,"nu"=>0,"pe"=>0,"bp"=>0,"bc"=>0,'diff'=>0],
            ["name" => "St Laurent Feuchy Es 2","pts"=>0,"jo"=> 0,"ga"=>0,"nu"=>0,"pe"=>0,"bp"=>0,"bc"=>0,'diff'=>0],
        ];

        foreach($array as $row)
        {        
            $equipe = new Equipe();
            $equipe->setName($row["name"]); 
            $equipe->setPts($row["pts"]);
            $equipe->setPts($row["pts"]);
            $equipe->setJo($row["jo"]);
            $equipe->setGa($row["ga"]);
            $equipe->setNu($row["nu"]);
            $equipe->setPe($row["pe"]);
            $equipe->setBp($row["bp"]);
            $equipe->setBc($row["bc"]);
            $equipe->setDiff($row["diff"]);
            $equipe->setCategorie($seniorA);
            $manager->persist($equipe);
            $equipes[] = $equipe;
        }  

        foreach($array2 as $row)
        {        
            $equipe = new Equipe();
            $equipe->setName($row["name"]); 
            $equipe->setPts($row["pts"]);
            $equipe->setPts($row["pts"]);
            $equipe->setJo($row["jo"]);
            $equipe->setGa($row["ga"]);
            $equipe->setNu($row["nu"]);
            $equipe->setPe($row["pe"]);
            $equipe->setBp($row["bp"]);
            $equipe->setBc($row["bc"]);
            $equipe->setDiff($row["diff"]);
            $equipe->setCategorie($seniorB);
            $manager->persist($equipe);
            $equipes[] = $equipe;
        }  

        
        return $equipes;
    }
   
    public function loadVisiteurs(ObjectManager $manager, array $equipes)
    {
        $matchJouer = 1;
        $score = 1;

        $visiteurs = [];
        $equipeIndexTilloy = 0;
        $tilloy = $equipes[$equipeIndexTilloy];

            $visiteur = new Visiteur();
            $visiteur->setScore($score);
            $visiteur->setEquipe($tilloy);
            $visiteur->getEquipe()->setga($matchJouer);
            $visiteur->getEquipe()->setbp($score);

            $manager->persist($visiteur);
            $visiteurs[] = $visiteur;
         
        return $visiteurs;


    }


    public function loadDomiciles(ObjectManager $manager, array $equipes, array $visiteurs)
    {
        $domiciles = [];
        $equipeIndex = 8;
        $equipe = $equipes[$equipeIndex];
        $visiteurIndex = 0;
        $visiteur = $visiteurs[$visiteurIndex];
        $ptsMatchGagne = 3;
        $ptsMatchNul = 1;
        $ga = 1;
        $matchJouer = 1;
        $bp = 4;
        $bc = $visiteur->getEquipe()->getbp();
        

        
        $domicile =  new Domicile();
        $domicile->setScore($bp);
        $domicile->setEquipe($equipe);

        $domicile->getEquipe()->setJo($matchJouer);
        $visiteur->getEquipe()->setJo($matchJouer);

        $domicile->getEquipe()->setGa($ga);

        $domicile->getEquipe()->setbp($bp);
        $visiteur->getEquipe()->setbc($bp);
        
        $domicile->getEquipe()->setBc($bc);

        $domicile->getEquipe()->setDiff($bp-$bc);
        $visiteur->getEquipe()->setDiff($bc-$bp);

        $domicile->setVisiteur($visiteur);
        $manager->persist($domicile);
        $domiciles[] = $domicile;


        if ($visiteur->getScore($visiteur) < $domicile->getScore($domicile)) {
            
            $pts = $domicile->getEquipe()->getPts();
            $ga = $domicile->getEquipe()->getGa();
            $bp = $visiteur->getScore($visiteur);
           return $domicile->getEquipe()->setPts($pts+$ptsMatchGagne); 

        }
        elseif ($visiteur->getScore($visiteur) > $domicile->getScore($domicile)) {
            
            $pts = $visiteur->getEquipe()->getPts();
           return $visiteur->getEquipe()->setPts($pts+$ptsMatchGagne); 
        }  

        elseif ($visiteur->getScore($visiteur) == $domicile->getScore($domicile)) {
            
            $ptsVisiteur = $visiteur->getEquipe()->getPts();
            $ptsDomicile = $domicile->getEquipe()->getPts();
            return $domicile->getEquipe()->setPts($ptsDomicile+$ptsMatchNul) && $visiteur->getEquipe()->setPts($ptsVisiteur+$ptsMatchNul) ; 
            
                }  

        return $domiciles;

    }
    public function loadPostsAndTags(ObjectManager $manager)
    {        
        
        $faker = Factory::create('fr_FR');


        for ($i = 1; $i <= 20; $i++) {
            $tag = new Tag();
            $tag->setName($faker->words($nb = 3, $asText = true));
            $manager->persist($tag);
        }

        $manager->flush();


        for ($i = 1; $i <= 20; $i++) {
            $post = new Post();
            $post->setTitre($faker->sentence($nbWords = 6, $variableNbWords = true));
            $post->setBody($faker->text($maxNbChars = 1000));
            $post->setPublishDate($faker->dateTimeBetween($startDate = '-1 year', $endDate = 'now', $timezone = null));
            $post->addTag($tag);
            $manager->persist($post);
        }

        $manager->flush();
    }

}
