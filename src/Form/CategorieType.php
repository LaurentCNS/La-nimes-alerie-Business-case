<?php

namespace App\Form;

use App\Entity\Animal;
use App\Entity\Categorie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategorieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('animal' , EntityType::class , [
                'class' => Animal::class,
                'choice_label' => 'libelle',
                'required' => true,
            ])
            ->add('parent' , EntityType::class , [
                'class' => Categorie::class,
                'label' => 'Association',
                'choice_label' => 'nom',
                'required' => false,
                'placeholder' => 'Categorie parent'
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
