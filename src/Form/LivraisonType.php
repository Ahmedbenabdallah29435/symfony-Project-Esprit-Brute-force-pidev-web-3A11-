<?php

namespace App\Form;


use App\Entity\Commande;
use App\Entity\Vehicule;
use App\Entity\Livraison;
use App\Entity\Utilisateurs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class LivraisonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
            ->add('dateLivraison',DateType::class)
           
            
            ->add('livreur', EntityType::class, [
           
                'class' => Utilisateurs::class,
        
                'choice_label' => function ($Utilisateurs) {
                    if($Utilisateurs->getEtat() == "Disponible"){return $Utilisateurs->getEmail();}
                    
                }])
            
            ->add('vehicule', EntityType::class, [
           
                'class' => Vehicule::class,
                'choice_label' => function ($Vehicule) {
                    if($Vehicule->getEtatVehicule() == "Disponible"){return $Vehicule->getVehicule();}

                }
                // used to render a select box, check boxes or radios
               // 'multiple' => true,
               // 'expanded' => true,
            ])
            
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Livraison::class,
        ]);
    }
}