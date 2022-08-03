<?php

namespace App\Controller;

use App\Repository\AnimalRepository;
use App\Repository\CategorieRepository;
use App\Repository\PanierRepository;
use App\Twig\HeaderExtension;
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
        private PanierRepository $panierRepository,
        private HeaderExtension $headerExtension,
      )
  {}

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {


        
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    
    
}
