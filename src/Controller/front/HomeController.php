<?php

namespace App\Controller\front;

use App\Entity\NbVisites;
use App\Repository\AnimalRepository;
use App\Repository\PanierRepository;
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
    public function index(AnimalRepository $animalRepository): Response
    {

        // Ajouter une ligne date dans la table nbVisites
        $nbVisites = new NbVisites();
        $nbVisites->setDateVisite(new \DateTime());
        $this->entityManager->persist($nbVisites);
        $this->entityManager->flush();

        
        return $this->render('front/home/index.html.twig', [
            'controller_name' => 'HomeController',
            'animals' => $animalRepository->findAll(),
        ]);
    }
    
    
}
