<?php

namespace App\Form;

use App\Entity\Joueur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;


class JoueurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('sexe')
            // ->add('cartonJaune')
            // ->add('cartonRouge')
            // ->add('but')
            // ->add('matchJouer')
            ->add('dateDeNaissance', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd', ])
            
            ->add('equipe')
            ->add('user', UserType::class, [
                // Masquage du label (le nom) du champ
                'label' => false,
            ])
            // ->add('categorie')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Joueur::class,
        ]);
    }
}
// ...

