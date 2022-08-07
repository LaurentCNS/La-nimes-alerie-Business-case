<?php

namespace App\Controller\stats;

use App\Repository\PanierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;


class ConversionCommandesController extends AbstractController
{

    public function __construct(
        private PanierRepository $panierRepository,
    )
    {}

    public function __invoke(): JsonResponse{
        $nbPanierPaye = $this->panierRepository->nbCommandes();
        $nbPanierCree = $this->panierRepository->nbPanierCrees();
        $conversion = round(($nbPanierPaye/ $nbPanierCree) * 100,2);
        return new JsonResponse(json_encode(['data' => $conversion]));
    }

}
