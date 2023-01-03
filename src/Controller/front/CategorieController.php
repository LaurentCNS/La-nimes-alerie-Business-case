<?php

namespace App\Controller\front;

use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/categorie')]
class CategorieController extends AbstractController
{
    #[Route('/{animal}', name: 'app_animal')]
    public function index($animal, ProduitRepository $produitRepository): Response
    {

        $productsByAnimal = $produitRepository->getProductsByAnimal($animal);

        dump($productsByAnimal);

        return $this->render('front/categorie/index.html.twig', [
            'controller_name' => 'AnimalController',
            'productsByAnimal' => $productsByAnimal,
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
