<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Marque;
use App\Entity\Produit;
use App\Entity\Promotion;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle')
            ->add('description')
            ->add('dateEntree')
            ->add('prixHt')
            ->add('estActif')
            ->add('tva')
            ->add('marque',EntityType::class , [
                'class' => Marque::class,
                'choice_label' => 'nom',
                'required' => true,
            ])
            ->add('promotion',EntityType::class , [
                'class' => Promotion::class,
                'choice_label' => 'pourcentage',
                'required' => false,
                'placeholder' => 'Aucune promotion'
            ])
            ->add('categorie',EntityType::class , [
                'class' => Categorie::class,
                'choice_label' => 'nom',
                'required' => true,
            ])
            //->add('client')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
