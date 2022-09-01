<?php

namespace App\Twig;

use App\Repository\AnimalRepository;
use App\Repository\CategorieRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class HeaderExtension extends AbstractExtension
{

    public function __construct(
        private AnimalRepository $animalRepository,
        private CategorieRepository $categorieRepository,
    ) { }


    public function getFunctions(): array
    {
        return [
            new TwigFunction('functionTwigGetAnimaux', [$this, 'getAnimaux']),
            new TwigFunction('functionTwigGetCategories', [$this, 'getCategories']),
            new TwigFunction('functionTwigGetSousCategories', [$this, 'getSousCategories']),
        ];
    }

    //Fonction pour récupérer tous les animaux
    public function getAnimaux(): array
    {
        $animals = $this->animalRepository->findAll();
        return $animals;
    }

    //Fonction du repository catégorie récupérer toutes les catégories
    public function getCategories(): array
    {
        $categories = $this->categorieRepository->findCategorie();
        return $categories;
    }

    //Fonction du repository catégorie récupérer toutes les sous-catégories
    public function getSousCategories(): array
    {
        $sousCategories = $this->categorieRepository->findSousCategorie();
        return $sousCategories;
    }


    
}
