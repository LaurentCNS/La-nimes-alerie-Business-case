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
            ->add('libelle', TextFilterType::class, [  // -> qui correspond au titre de la table ou trier (categorie.nom) et à rappeler dans le twig -> {{ form_widget(filters.nom) }}</th>
                'condition_pattern' => FilterOperands::STRING_CONTAINS, // =>  en sql 'LIKE %xxxx%'
            ])

            // POUR UNE LISTE
            ->add('categorie', EntityFilterType::class, [ // -> qui correspond au titre de la table ou trier (categorie.animal) et à rappeler dans le twig -> {{ form_widget(filters.animal) }}</th>
                'class' => Categorie::class,  // la class ou on va chercher la liste
                'choice_label' => 'nom', // qui correspond au titre ou chercher (categorie.animal.libelle)
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nom', 'ASC')  // trier par ordre
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