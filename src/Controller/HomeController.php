<?php

namespace App\Controller;

use App\Repository\AnimalRepository;
use App\Repository\CategorieRepository;
use App\Repository\PanierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    public function __construct(        
        private EntityManagerInterface $entityManager,
        private AnimalRepository $animalRepository,
        private CategorieRepository $categorieRepository,
        private PanierRepository $panierRepository
      )
  {}

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        // recuperer les categories dans la base de données
        $animals = $this->animalRepository->findAll();

        // recuperer les categorie dont parent n'est pas null
        $categories = $this->categorieRepository->findAll();

        // recuperer les sous-catégories
        $sousCategories = $this->categorieRepository->findByParent();

        $nbCommande = $this->panierRepository->nbCommandes();
        dump($nbCommande);

        
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'animals' => $animals,
            'categories' => $categories,
            'sousCategories' => $sousCategories
        ]);
    }
    
    
}
