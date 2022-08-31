<?php

namespace App\Controller\front;

use App\Entity\Avis;
use App\Repository\AvisRepository;
use App\Repository\CategorieRepository;
use App\Repository\ProduitRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController
{


    #[Route('/produit/{slug}', name: 'app_produit')]
    public function index($slug, ProduitRepository $produitRepository, CategorieRepository $categorieRepository,
                          PaginatorInterface $paginator, Request $request): Response
    {
        $produit = $produitRepository->getProductBySlug($slug);
        $categories = $categorieRepository->findCategorie();

        // creation d'un tableau d'avis pour le produit
        $avisArray = [];
        foreach ($produit->getAvis() as $avis) {
            $avisArray[] = $avis;
        }

        // Paginator
        $avis = $paginator->paginate(
            $avisArray,
            $request->query->getInt('page',1),8
        );


        return $this->render('front/produit/index.html.twig', [
            'controller_name' => 'ProduitController',
            'produit' => $produit,
            'categories' => $categories,
            'avisProduit' => $avis,
        ]);
    }
}
