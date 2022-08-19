<?php

namespace App\Form;

use App\Entity\Adresse;
use App\Entity\Genre;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdresseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('genre' , EntityType::class, [
                'class' => Genre::class,
                'choice_label' => 'libelle',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('g')
                        ->orderBy('g.libelle', 'ASC')
                        ;
                }
            ])
            ->add('nom')
            ->add('prenom')
            ->add('ligne1')
            ->add('ligne2')
            ->add('ligne3')
            ->add('codePostal')
            ->add('ville')
            ->add('pays')
            ->add('telephone')
            ->add('estPrincipale')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adresse::class,
        ]);
    }
}
