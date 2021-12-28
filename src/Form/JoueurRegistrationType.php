<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Joueur;
// use App\Entity\Tag;
use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class JoueurRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',TextType::class,[
                'label' => false,
                'attr' =>[
                    'placeholder' => 'Nom'
                ]
            ])
            ->add('prenom',TextType::class,[
                'label' => false,
                'attr' =>[
                    'placeholder' => 'Prenom'
                ]
            ])
            ->add('dateDeNaissance', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
            ])            
            ->add('sexe', ChoiceType::class, [
                'choices' => [
                    'H' => 'H',
                    'F' => 'F'
                ],
                'expanded' => true 
            ])
            ->add('user', RegistrationFormType::class, [
                // Masquage du label (le nom) du champ
                'label' => false,
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
