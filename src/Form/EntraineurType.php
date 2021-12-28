<?php

namespace App\Form;

use App\Entity\Entraineur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntraineurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('user')
            ->add('categories', CategorieType::class)

            ->add('user', UserType::class, [
                // Masquage du label (le nom) du champ
                'label' => false,
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Entraineur::class,
        ]);
    }
}
