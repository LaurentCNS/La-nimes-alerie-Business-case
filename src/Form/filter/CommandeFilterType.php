<?php

namespace App\Form\filter;


use Doctrine\ORM\EntityRepository;
use Lexik\Bundle\FormFilterBundle\Filter\FilterOperands;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\DateRangeFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\EntityFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\TextFilterType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CommandeFilterType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            // POUR UNE RECHERCHE
            ->add('numeroCommande', TextFilterType::class, [
                'condition_pattern' => FilterOperands::STRING_CONTAINS,
            ])


            // POUR UN DATE PICKER
            ->add('datePaiement', DateRangeFilterType::class, [
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
    }
}