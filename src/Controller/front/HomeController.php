<?php

namespace App\Controller\front;

use App\Entity\NbVisites;
use App\Repository\AnimalRepository;
use App\Repository\PanierRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    public function __construct(        
        private EntityManagerInterface $entityManager,
      )
  {}


    #[Route('/', name: 'app_home')]
    public function index(AnimalRepository $animalRepository, ProduitRepository $produitRepository): Response
    {

        // Ajouter une ligne date dans la table nbVisites
        $nbVisites = new NbVisites();
        $nbVisites->setDateVisite(new \DateTime());
        $this->entityManager->persist($nbVisites);
        $this->entityManager->flush();

        // Récupérer les meilleurs produits
        $produits = $produitRepository->getProducts();

        // Ajouter dans un tableau d'objet les produits ayant une moyenne de note supérieure à 4.5
        $meilleursProduits = [];
        foreach ($produits as $produit) {
            if ($produit->getMoyenne() > 4.5) {
                $meilleursProduits[] = $produit;
            }
        }
        // Renvoyer seulement les 6 premiers produits
        $meilleursProduits = array_slice($meilleursProduits, 0, 6);


        // Récupérer les nouveaux produits
        $nouveauxProduits = $produitRepository->getNewProducts();

        // Récupérer les produits en promotions
        $promoProduits = $produitRepository->getPromoProducts();

        
        return $this->render('front/home/index.html.twig', [
            'controller_name' => 'HomeController',
            'animals' => $animalRepository->findAll(),
            'meilleursProduits' => $meilleursProduits,
            'nouveauxProduits' => $nouveauxProduits,
            'promoProduit' => $promoProduits,
        ]);
    }
    
    
}
