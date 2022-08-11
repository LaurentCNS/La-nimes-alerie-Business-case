<?php

namespace App\Form;

use App\Entity\Animal;
use App\Entity\Categorie;
use Doctrine\ORM\EntityRepository;
use Lexik\Bundle\FormFilterBundle\Filter\FilterOperands;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\EntityFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\TextFilterType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CategorieFilterType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            // POUR UNE RECHERCHE
            ->add('nom', TextFilterType::class, [  // -> qui correspond au titre de la table ou trier (categorie.nom) et à rappeler dans le twig -> {{ form_widget(filters.nom) }}</th>
                'condition_pattern' => FilterOperands::STRING_CONTAINS, // =>  en sql 'LIKE %xxxx%'
            ])

            // POUR UNE LISTE
            ->add('animal', EntityFilterType::class, [ // -> qui correspond au titre de la table ou trier (categorie.animal) et à rappeler dans le twig -> {{ form_widget(filters.animal) }}</th>
                'class' => Animal::class,  // la class ou on va chercher la liste
                'choice_label' => 'libelle', // qui correspond au titre ou chercher (categorie.animal.libelle)
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->orderBy('p.libelle', 'ASC')  // trier par ordre
                        ;
                }
            ])

        ;
    }
}