<?php

namespace App\Controller;

use App\Entity\Equipe;
use App\Repository\EquipeRepository;
use App\Entity\Domicile;
use App\Repository\DomicileRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     * @Route("/test", name="test")
     */
    public function index(EquipeRepository $equipeRepository, DomicileRepository $domicileRepository): Response
    {
        $point = -100;
        $equipe = $equipeRepository->findByPointAndDiff($point);
        dump($equipe);

        $equipe = $equipeRepository->find(1);
        $equipe->getDomicile();
        dump($equipe);
        $rencontres = $domicileRepository->findAll();
        $filterRencontres = [];
        
        for ($i=0; $i <= count($rencontres) ; $i++) { 
            dump($rencontres);
            dump($rencontre = $domicileRepository->find($i));
            if ($rencontre != null && $rencontre->getScore()>$rencontre->getVisiteur()->getScore()) {         
                $pts = $rencontre->getEquipe()->getPts();
                $rencontre->getEquipe()->setPts($pts+3);
                $filterRencontres[]=$rencontre;
            }elseif ($filterRencontres === $rencontres) {
                return null;
            }
        }
        dump($filterRencontres);
        $rencontres = $filterRencontres;
        dump($rencontres);

        exit();
    }
}
