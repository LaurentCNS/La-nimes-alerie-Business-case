<?php

namespace App\Controller\stats;

use App\Repository\NbVisitesRepository;
use App\Repository\PanierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class ConversionPaniersController extends AbstractController
{

    public function __construct(
        private PanierRepository $panierRepository,
        private NbVisitesRepository $nbVisitesRepository
    )
    {}

    public function __invoke(): JsonResponse{
        $nbPanier = $this->panierRepository->nbPanierCrees();
        $nbVisites = $this->nbVisitesRepository->compteurVisite();
        $conversion = round(($nbPanier/ $nbVisites) * 100,2);
        return new JsonResponse($conversion);
    }

}
