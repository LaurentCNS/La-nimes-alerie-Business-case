<?php

namespace App\Controller\stats;

use App\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class NouveauxClientsController extends AbstractController
{
    public function __construct(
        private ClientRepository $clientRepository,
    )
    {}



    public function __invoke(): JsonResponse{
        $nouveauxClients = $this->clientRepository->nbNouveauxClients();
        return new JsonResponse($nouveauxClients);
    }
}
