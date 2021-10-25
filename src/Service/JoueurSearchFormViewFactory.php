<?php

namespace App\Service;

use App\Form\JoueurSearchType;
use Symfony\Component\Form\FormFactoryInterface;

class JoueurSearchFormViewFactory
{
    private $formFactory;

    // La classe "FormFactoryInterface" permet de créer des formulaire
    public function __construct(FormFactoryInterface $formFactory)
    {
        // Sauvegarde du service de création de formulaire
        $this->formFactory = $formFactory;
    }

    public function create()
    {
        // Création du formulaire de recherche d'un Joueur puis
        // création de la vue du formulaire.
        return $this->formFactory
            ->create(JoueurSearchType::class)
            ->createView()
        ;
    }
}