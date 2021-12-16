<?php

namespace App\Controller;

Trait AlgoCategorieTrait
    {
        public function CategoriOfPlayer($sexe,$age,$joueurRepository,$equipeRepository,$categorieRepository,$joueur,$tilloy){
            // dump($tilloy);

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
            
            if($age->format('%y') >= "8" && $age->format('%y') < "10") {
                $nom = $u9;

            }if ($age->format('%y') >= "10" && $age->format('%y') < "12"){
                $nom = $u11;

            }if ($age->format('%y') >= "12" && $age->format('%y') < "14") {
                $nom = $u13;

            }if ($age->format('%y') >= "14" && $age->format('%y') < "17")  {
                $nom = $u15;

            }if ($age->format('%y') >= "16" && $age->format('%y') < "18") {
                $nom = $u17;

            }if ($age->format('%y') >= "18" && $age->format('%y') < "35") {
                $nom = $senior;

            }if ($age->format('%y') > "35") {
                $nom = $veteran;

            }


            $groupe = 'A';
            $nbMaxPerGroupe = 25;
            $joueurPerSectionGroupeName = $joueurRepository->findBySectionGroupeName($section,$groupe,$nom);
            $joueurCountPerGroupe = count($joueurPerSectionGroupeName)-1;

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
            

        }
    }
