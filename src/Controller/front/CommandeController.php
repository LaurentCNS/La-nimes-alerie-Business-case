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

        // récupère les infos du client
        $client = $this->getUser();
        return $this->render('front/commande/index.html.twig', [
            'controller_name' => 'CommandeController',
        ]);
    }
}
