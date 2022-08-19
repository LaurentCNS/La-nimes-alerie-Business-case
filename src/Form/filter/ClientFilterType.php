<?php

namespace App\Form\filter;

use App\Entity\Animal;
use App\Entity\Categorie;
use Doctrine\ORM\EntityRepository;
use Lexik\Bundle\FormFilterBundle\Filter\FilterOperands;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\DateRangeFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\EntityFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\TextFilterType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ClientFilterType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            // POUR UNE RECHERCHE
            ->add('username', TextFilterType::class, [
                'condition_pattern' => FilterOperands::STRING_CONTAINS,
            ])
            ->add('email', TextFilterType::class, [
                'condition_pattern' => FilterOperands::STRING_CONTAINS,
            ])

            // POUR UN DATE PICKER
            ->add('dateInscription', DateRangeFilterType::class, [
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
