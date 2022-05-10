<?php

namespace App\Form;

use App\Entity\Vehicule;
use App\Entity\Utilisateurs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class MotoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        
        ->add('matricule')
        ->add('couleur',ChoiceType::class,[
            'choices' => [
                "Rouge" => "Rouge",
                "Bleu"  => "Bleu",
                "Noire"  => "Noire",
                "Blanc"  => "blanc",
            ]
        ])
        ->add('typeVehicule',ChoiceType::class,[
            'choices' => [
                "Moto" => "Moto",
                ]
                ])

        ->add('marque',ChoiceType::class,[
            'choices' => [
                "Liberty" => "Liberty",
                "ovetto"  => "oveto",
                "Forza"  => "Forza",
                "Yamaha"  => "Yamaha",
            ]
        ])
        ->add('DateEntretient',DateType::class)
        
    ;
}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vehicule::class,
        ]);
    }
}
