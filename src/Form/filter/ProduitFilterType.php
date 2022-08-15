<?php

namespace App\Form\filter;


use App\Entity\Categorie;
use Doctrine\ORM\EntityRepository;
use Lexik\Bundle\FormFilterBundle\Filter\FilterOperands;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\DateRangeFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\EntityFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\TextFilterType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ProduitFilterType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            // POUR UNE RECHERCHE
            ->add('libelle', TextFilterType::class, [
                'condition_pattern' => FilterOperands::STRING_CONTAINS,
            ])

            // POUR UNE LISTE
            ->add('categorie', EntityFilterType::class, [
                'class' => Categorie::class,
                'choice_label' => 'nom',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nom', 'ASC')
                        ;
                }
            ])
            // POUR UN DATE PICKER
            ->add('dateEntree', DateRangeFilterType::class, [
                'left_date_options' => [
                    'label' => 'de',
                    'widget' => 'single_text',
                ],
                'right_date_options' => [
                    'label' => 'à',
                    'widget' => 'single_text',
                ]
            ])
        ;

        ;
    }
}