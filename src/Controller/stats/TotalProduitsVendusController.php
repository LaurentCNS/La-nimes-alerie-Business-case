<?php

namespace App\Controller\stats;


use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class TotalProduitsVendusController extends AbstractController
{

    public function __construct(
        private ProduitRepository $produitRepository,
    )
    {}

    public function __invoke(): JsonResponse{
        $produitsVendus = $this->produitRepository->findProduitsBestSelling();
        return new JsonResponse($produitsVendus);
    }


}
