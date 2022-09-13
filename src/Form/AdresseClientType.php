<?php

namespace App\Form;

use App\Entity\Adresse;
use App\Entity\Genre;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdresseClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('genre', EntityType::class, [
                'class' => Genre::class,
                'choice_label' => 'libelle',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('g')
                        ->orderBy('g.libelle', 'DESC');
                }
            ])
            ->add('nom', TextType::class,
            )
            ->add('prenom',TextType::class,
            )
            ->add('ligne1', TextType::class, [
                'label' => 'Numéro et nom de la rue',
            ])
            ->add('ligne2',TextType::class, [
                'label' => 'Bâtiment, étage, porte',
                'required' => false,
            ])
            ->add('ligne3',TextType::class, [
                'label' => 'Complément d\'adresse',
                'required' => false,
            ])
            ->add('codePostal', NumberType::class,
            )
            ->add('ville',TextType::class,
            )
            ->add('pays', TextType::class,
            )
            ->add('telephone', TextType::class,
            )
            // bouton submit
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => [
                    'class' => 'btn btn-primary mt-3',
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adresse::class,
        ]);
    }
}