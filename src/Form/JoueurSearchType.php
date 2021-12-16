<?php

namespace App\Form;

use App\Entity\Categorie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JoueurSearchType extends AbstractType
{
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->setAction($this->urlGenerator->generate('app_search'))
        ->setMethod('get')

        ->add('section', ChoiceType::class, [
            'choices' => [
                'Masculine' => 'Masculine',
                'Feminine' => 'Feminine'
            ]
        ])
        ->add('categorie', ChoiceType::class, [
            'choices' => [
                "U8-U9"=>"U8-U9",
                "U10-U11"=>"U10-U11",
                "U12-U13"=>"U12-U13",
                "U14-U15"=>"U14-U15",
                "U16-U17"=>"U16-U17",
                "Senior"=>"Senior",
                "Veteran"=>"Veteran"
            ]
        ])

        ->add('groupe', ChoiceType::class, [
            'choices' => [
                'A' => 'A',
                'B' => 'B',
                'C' => 'C'
            ]
        ])

;
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
        ]);

    }

    public function getBlockPrefix()
    {
        return '';
    }
}

