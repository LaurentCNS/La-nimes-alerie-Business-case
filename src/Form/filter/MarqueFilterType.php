<?php

namespace App\Form\filter;

use App\Entity\Animal;
use Doctrine\ORM\EntityRepository;
use Lexik\Bundle\FormFilterBundle\Filter\FilterOperands;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\EntityFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\TextFilterType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class MarqueFilterType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            // POUR UNE RECHERCHE
            ->add('nom', TextFilterType::class, [
                'condition_pattern' => FilterOperands::STRING_CONTAINS,
            ])

        ;
    }
}