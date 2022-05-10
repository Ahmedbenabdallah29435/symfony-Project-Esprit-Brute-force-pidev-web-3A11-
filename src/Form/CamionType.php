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

class CamionType extends AbstractType
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
                "Camion" => "Camion",
                ]
                ])

        ->add('marque',ChoiceType::class,[
            'choices' => [
                "Renault" => "Renault",
                "Volswagen"  => "Volswagen",
                "Iveco"  => "Iveco",
                "DAF XF"  => " DAF XF",
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
