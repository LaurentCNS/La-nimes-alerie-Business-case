<?php

namespace App\Controller\front;

use App\Repository\CategorieRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController
{
    #[Route('/produit/{slug}', name: 'app_produit')]
    public function index($slug, ProduitRepository $produitRepository, CategorieRepository $categorieRepository): Response
    {
        $produit = $produitRepository->getProductBySlug($slug);
        dump($produit);
        $categories = $categorieRepository->findCategorie();


        return $this->render('front/produit/index.html.twig', [
            'controller_name' => 'ProduitController',
            'produit' => $produit,
            'categories' => $categories,
        ]);
    }
}
