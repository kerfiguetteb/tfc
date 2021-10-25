<?php

namespace App\Form;

use App\Entity\Equipe;
use App\Entity\Joueur;
use App\Entity\Tag;
use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JoueurRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user', RegistrationFormType::class, [
                // Masquage du label (le nom) du champ
                'label' => false,
            ])
            ->add('nom')
            ->add('prenom')
            ->add('dateDeNaissance')
            ->add('sexe')
            ->add('equipe', EntityType::class, [
                // looks for choices from this entity
                'class' => Equipe::class,

                // uses the User.username property as the visible option string
                'choice_label' => function (Equipe $equipe) {
                    return "{$equipe->getName()}";
                },
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->orderBy('s.name', 'ASC')
                    ;
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Joueur::class,
        ]);
    }
}
