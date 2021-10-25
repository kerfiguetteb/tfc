<?php

namespace App\Form;

use App\Entity\Domicile;
use App\Entity\Visiteur;
use App\Form\VisiteurType;
use App\Entity\Equipe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DomicileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('equipe', EntityType::class,[
            'class' => Equipe::class,
            'choice_label'=> function(Equipe $equipe){
                return "{$equipe->getName()}";
            },
            ])        
        ->add('score')
            ->add('visiteur',VisiteurType::class )
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Domicile::class,
        ]);
    }
}
