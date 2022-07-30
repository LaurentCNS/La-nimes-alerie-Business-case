<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    public function __construct(        
        private EntityManagerInterface $entityManager,
        private CategorieRepository $categorieRepository,
      )
  {}

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        // recuperer les categories dans la base de données
        $categories = $this->categorieRepository->findBy(['parent' => null]);

        // recuperer les categorie dont parent n'est pas null
        $sousCategories = $this->categorieRepository->findAll();
        dump($sousCategories);

        
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'categories' => $categories,
            'sousCategories' => $sousCategories,
        ]);
    }
    
    
}
