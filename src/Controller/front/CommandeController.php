<?php

namespace App\Controller\front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommandeController extends AbstractController
{
    #[Route('/commande/{id}', name: 'app_commande')]
    public function index(): Response
    {

        // recupère les infos du client
        $client = $this->getUser();
        return $this->render('back/commande/index.html.twig', [
            'controller_name' => 'CommandeController',
        ]);
    }
}
