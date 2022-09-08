<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Marque;
use App\Entity\Produit;
use App\Entity\Promotion;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle')
            ->add('marque')
            ->add('categorie')
            ->add('description', TextareaType::class)
            //ajouter une photo principale
            ->add('photoPrincipale', FileType::class, [
                'label' => 'Photo principale (obligatoire)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File(
                        maxSize: '2048k',
                        mimeTypes: ['image/png', 'image/jpeg'],
//                        mimeTypesMessage: 'Ce format d\'image n\'est pas pris en compte',
                    )
                ]
            ])
            //ajouter une photo secondaire
            ->add('photoSecondaire', FileType::class, [
                'label' => 'Photo secondaire',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File(
                        maxSize: '2048k',
                        mimeTypes: ['image/png', 'image/jpeg'],
//                        mimeTypesMessage: 'Ce format d\'image n\'est pas pris en compte',
                    )
                ]
            ])
            ->add('prixHt')
            ->add('quantiteStock')
            ->add('tva')
            ->add('marque',EntityType::class , [
                'class' => Marque::class,
                'choice_label' => 'nom',
                'required' => true,
            ])
            ->add('promotion',EntityType::class , [
                'class' => Promotion::class,
                // choixe la propriété à afficher dans la liste déroulante avec un calcule de promotion
                'choice_label' => function (Promotion $promotion) {
                    return $promotion->getPourcentage() . '%';
                },
                'required' => false,
                'placeholder' => 'Aucune promotion'
            ])
            ->add('categorie',EntityType::class , [
                'class' => Categorie::class,
                'choice_label' => 'nom',
                'required' => true,
            ])
            ->add('estActif')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
