<?php

namespace App\Controller\front;

use App\Repository\AnimalRepository;
use App\Repository\CategorieRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/categorie')]
class CategorieController extends AbstractController
{
    #[Route('/{animal}', name: 'app_animal')]
    public function index($animal, ProduitRepository $produitRepository, CategorieRepository $categorieRepository, AnimalRepository $animalRepository): Response
    {

        $idAnimal = $animalRepository->findOneBy(['libelle' => $animal]);

        $productsByAnimal = $produitRepository->getProductsByAnimal($animal);
        $categories = $categorieRepository->findCategorieByAnimal($idAnimal);

        return $this->render('front/categorie/index.html.twig', [
            'controller_name' => 'AnimalController',
            'animal' => $animal,
            'productsByAnimal' => $productsByAnimal,
            'categories' => $categories,
        ]);
    }



    #[Route('/{animal}/{categorie}', name: 'app_categorie')]
    public function productByCategorie($animal,$categorie, ProduitRepository $produitRepository): Response
    {

        $getProductsByAnimalAndCategorie = $produitRepository->getProductsByAnimalAndCategorie($animal, $categorie);

        dump($getProductsByAnimalAndCategorie);

        return $this->render('front/categorie/categorie.html.twig', [
            'controller_name' => 'CategorieController',
            'getProductsByAnimalAndCategorie' => $getProductsByAnimalAndCategorie,
        ]);
    }

}
