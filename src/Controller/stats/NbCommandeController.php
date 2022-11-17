<?php

namespace App\Controller\stats;

use App\Repository\PanierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;


class NbCommandeController extends AbstractController
{

    public function __construct(        
        private PanierRepository $panierRepository
      )
  {}

    public function __invoke(): JsonResponse{
        $nbCommande = $this->panierRepository->nbCommandes();
        return new JsonResponse($nbCommande);
    }

   
}
