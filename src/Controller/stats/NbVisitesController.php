<?php

namespace App\Controller\stats;


use App\Repository\NbVisitesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class NbVisitesController extends AbstractController
{
    public function __construct(
        private NbVisitesRepository $nbVisitesRepository
    )
    {}

    public function __invoke(): JsonResponse{
        $visites = $this->nbVisitesRepository->compteurVisite();
        return new JsonResponse($visites);
    }
}
