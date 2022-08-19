<?php

namespace App\Form\filter;

use App\Entity\Animal;
use App\Entity\Categorie;
use App\Entity\Client;
use Doctrine\ORM\EntityRepository;
use Lexik\Bundle\FormFilterBundle\Filter\FilterOperands;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\EntityFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\TextFilterType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AdresseFilterType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            // POUR UNE RECHERCHE
            ->add('nom', TextFilterType::class, [
                'condition_pattern' => FilterOperands::STRING_CONTAINS,
            ])

            ->add('prenom', TextFilterType::class, [
                'condition_pattern' => FilterOperands::STRING_CONTAINS,
            ])

            ->add('ville', TextFilterType::class, [
                'condition_pattern' => FilterOperands::STRING_CONTAINS,
            ])

            ->add('client', EntityFilterType::class, [
                'class' => Client::class,
                'choice_label' => 'username',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.username', 'ASC')
                        ;
                }
            ])

        ;
    }
}