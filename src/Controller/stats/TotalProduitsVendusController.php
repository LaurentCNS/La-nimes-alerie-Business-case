<?php

namespace App\Controller\stats;

use App\Repository\PanierRepository;
use App\Repository\LigneRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TotalProduitsVendusController extends AbstractController
{

    public function __construct(
        private ProduitRepository $produitRepository,
    )
    {}

    public function __invoke(): JsonResponse{
        $produitsVendus = $this->produitRepository->findProduitsPlusVendus();
        return new JsonResponse(json_encode(['data' => $produitsVendus]));
    }


}
