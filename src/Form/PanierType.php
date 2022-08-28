<?php

namespace App\Form;

use App\Entity\Adresse;
use App\Entity\Client;
use App\Entity\MoyenPaiement;
use App\Entity\Panier;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PanierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('statut', ChoiceType::class, [
                'label' => 'Sélectionner un status',
                // 'multiple' => true, //pour pouvoir selectionner plusieurs choix
                // 'expanded' => true, //pour les checkboxs sans multiple ce sont des radios
                'choices' => [
                    'Panier créé' => '100',
                    'Commande payée' => '200',
                    'Panier abandonné' => '300',
                    'Commande en préparation' => '400',
                    'Commande expédiée' => '500',
                    'Commande livrée' => '600',
                    'Commande annulée' => '700',
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Panier::class,
        ]);
    }
}
