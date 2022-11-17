<?php

namespace App\Controller\stats;

use App\Repository\PanierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class PanierMoyenController extends AbstractController
{
    public function __construct(
        private PanierRepository $panierRepository,

    )
    {}

    public function __invoke(): JsonResponse{
        $nbTotalVentes = $this->panierRepository->montantTotalVentes();
        $nbCommande = $this->panierRepository->nbCommandes();
        $panierMoyen = round($nbTotalVentes / $nbCommande,2);
        return new JsonResponse($panierMoyen);
    }



}
