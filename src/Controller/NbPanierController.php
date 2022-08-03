<?php

namespace App\Controller;

use App\Repository\PanierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;


class NbPanierController extends AbstractController
{

    public function __construct(        
        private PanierRepository $panierRepository
      )
  {}

    public function __invoke(): JsonResponse{
        $nbCommande = $this->panierRepository->nbCommandes();
        return new JsonResponse(json_encode(['data' => $nbCommande]));
    }

   
}
