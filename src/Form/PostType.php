<?php

namespace App\Form;

use App\Entity\Post;
use App\Form\CategorieType;
use App\Form\ChoicesType;
use App\Form\DateTimePickerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;





class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre',TextType::class,[
                'label' => false,
                'attr' => [
                    'placeholder' => 'titre'
                ]


            ])
            ->add('body',TextareaType::class,[
                'label_attr' => [
                    'class' => 'd-none',
                ],

            ])
            ->add('pictureFiles', FileType::class, [
                'required' => false,
                'multiple' => true
            ])


            ->add('publishDate', DateType::class, [
                'widget' => 'single_text',
                ])

            ->add('categorie', CategorieType::class,[
                'required'=>false
            ])

;
        
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
            'translation_domain' => 'forms'

        ]);
    }
    
    private function getChoices()
    {
        $choices = Post::HEAT;
        $output = [];
        foreach($choices as $k => $v) {
            $output[$v] = $k;
        }
        return $output;
    }
}
