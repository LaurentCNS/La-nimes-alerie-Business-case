<?php

namespace App\Controller\stats;

use App\Repository\PanierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class PourcentagePanierAbandonnéesController extends AbstractController
{
    public function __construct(
        private PanierRepository $panierRepository,
    )
    {}

    public function __invoke(): JsonResponse{
        $panierValide = $this->panierRepository->nbCommandes();
        $panierAbandonnees = $this->panierRepository->nbPanierAnbandonnés();
        $pourcentage = round( $panierAbandonnees / ($panierValide+$panierAbandonnees) * 100,2);
        return new JsonResponse(json_encode(['data' => $pourcentage]));
    }
}
