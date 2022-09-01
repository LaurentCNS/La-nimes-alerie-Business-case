<?php

namespace App\Controller\front;

use App\Entity\Avis;
use App\Entity\Produit;
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
                         Produit $produit, PaginatorInterface $paginator, Request $request): Response
    {

        // Récupération des catégories pour le menu
        $categories = $categorieRepository->findCategorie();

        // Récupération du produit sélectionné par le slug envoyer en paramètre
        $produitSelect = $produitRepository->getProductBySlug($slug);


            // Post-Traitement
            // Moyenne des notes pour produit.avis.note
            $moyenne = $produit->getAverageNote($produitSelect);

            // creation d'un tableau d'avis pour le paginator et d'un compteur pour le nombre d'avis
            $avisArray = [];
            $nbTotalNote = 0;

            foreach ($produitSelect->getAvis() as $nb => $avis) {
                if ($avis) {
                    $avisArray[] = $avis;
                    // Compteur d'avis
                    $nbTotalNote = $nb + 1;
                }
            }

            $avisPaginate = $paginator->paginate(
                $avisArray,
                $request->query->getInt('page',1),8
            );


        return $this->render('front/produit/index.html.twig', [
            'controller_name' => 'ProduitController',
            'produit' => $produitSelect,
            'categories' => $categories,
            'avisProduit' => $avisPaginate,
            'moyenne' => $moyenne,
            'nbTotalNote' => $nbTotalNote,
        ]);
    }
}
