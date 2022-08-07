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
        $dateDebut = new \DateTime('2000-01-01');
        $dateFin = new \DateTime('now');
        $nouveauxClients = $this->clientRepository->nbNouveauxClients($dateDebut, $dateFin);
        return new JsonResponse(json_encode(['data' => $nouveauxClients]));
    }
}
