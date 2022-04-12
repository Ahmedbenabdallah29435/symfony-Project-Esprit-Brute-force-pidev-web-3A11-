<?php

namespace App\Form;
use App\Entity\Categorie;
use App\Entity\Joueur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class JoueurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomjoueur')
            ->add('prenomjoueur')


            ->add('datedenaissance', DateType::class, [
                'label' => 'Start date',
                'label_attr' => array('class' => 'sr-only'),
                'placeholder' => [
                    'year' => 'Year',
                    'month' => 'Month',
                ],
                'years' => range(date('Y')-70, date('Y')),
                'data' => new \DateTime(),
            ])

            ->add('age')
            ->add('sexe', ChoiceType::class, array(
                'choices' => array(
                    'Homme' => 'Homme',
                    'Femme' => 'Femme',
                )
            ))
            ->add('ville')
            ->add('imgjoueur', FileType::class, array('data_class' => null, 'required' => false), ['label' => true,])
            //   ->add('myid');
            ->add('categorie', EntityType::class, [
                // looks for choices from this entity
                'class' => Categorie::class,

                // uses the User.username property as the visible option string
                'choice_label' => 'NomCategorie',

                // used to render a select box, check boxes or radios
                // 'multiple' => true,
                // 'expanded' => true,
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Joueur::class,
        ]);
    }
}
