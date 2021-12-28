<?php

namespace App\Form;

use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategorieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        
        ->add('section', ChoiceType::class, [
            'choices' => [
                'Masculine' => 'Masculine',
                'Feminine' => 'Feminine'
            ],
            'label'=> false,
            'required'=>false

        ])
        ->add('nom', ChoiceType::class, [
            'choices' => [
                "U8-U9"=>"U8-U9",
                "U10-U11"=>"U10-U11",
                "U12-U13"=>"U12-U13",
                "U14-U15"=>"U14-U15",
                "U16-U17"=>"U16-U17",
                "Senior"=>"Senior",
                "Veteran"=>"Veteran"
            ],  
            'label'=> false,
            'required'=>false

                          
        ])

        ->add('groupe', ChoiceType::class, [
            'choices' => [
                'A' => 'A',
                'B' => 'B',
                'C' => 'C'
            ],
            'label'=> false,
            'required'=>false
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Categorie::class,
        ]);
    }
}
