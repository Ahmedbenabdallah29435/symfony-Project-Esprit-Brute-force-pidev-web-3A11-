<?php

namespace App\Form;

use App\Entity\Boutique;
use App\Entity\Utilisateurs;
use App\Repository\UtilisateursRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class BoutiqueType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options ): void
    {
        $builder
            ->add('nomBoutique')
            ->add('descBoutique')
            ->add('adresseBoutique')
           
            ->add('images', FileType::class,[
                'label' => false,
                'multiple' => true,
                'mapped' => false,
                'required' => false
            ])
            ->add('latitude')
            ->add('longitude')
        
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Boutique::class,
        
        ]);
    }

    
}
