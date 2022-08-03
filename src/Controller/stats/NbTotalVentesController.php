<?php

namespace App\Controller\stats;

use App\Repository\PanierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;


class NbTotalVentesController extends AbstractController
{

    public function __construct(        
        private PanierRepository $panierRepository
      )
  {}

    public function __invoke(): JsonResponse{
        $nbTotalVentes = $this->panierRepository->montantTotalVentes();
        return new JsonResponse(json_encode(['data' => $nbTotalVentes]));
    }

   
}
