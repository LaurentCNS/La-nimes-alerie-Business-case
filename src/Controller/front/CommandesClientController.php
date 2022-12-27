<?php

namespace App\Controller\front;

use App\Entity\Panier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/commandes/client')]
class CommandesClientController extends AbstractController
{
    #[Route('/', name: 'app_commandes_client')]
    public function index(EntityManagerInterface $entityManager): Response
    {

        // Si l'utilisateur n'est pas connecté, on le redirige vers la page de connexion
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_register');
        }

        // On récupère les paniers du client avec le statut 200
        $paniers = $entityManager->getRepository(Panier::class)->findBy(['client' => $this->getUser()]);

        // On enlève les paniers avec le statut 100
        foreach ($paniers as $key => $panier) {
            if ($panier->getStatut() == 100) {
                unset($paniers[$key]);
            }
        }

        // Trier les paniers par datePaiement (du plus récent au plus ancien)
        usort($paniers, function ($a, $b) {
            return $a->getDatePaiement() < $b->getDatePaiement();
        });

        return $this->render('front/commandes_client/index.html.twig', [
            'controller_name' => 'CommandesClientController',
            'paniers' => $paniers,
        ]);
    }

    // CHOIX DE LA COMMANDE
    #[Route('/{choice}', name: 'app_commandes_client_choice')]
    public function addAddress(EntityManagerInterface $entityManager, $choice): Response
    {

        // Si l'utilisateur n'est pas connecté, on le redirige vers la page de connexion
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_register');
        }

        // On récupère le panier en vérifiant que le client est bien le propriétaire du panier
        $panier = $entityManager->getRepository(Panier::class)->findOneBy(['client' => $this->getUser(), 'id' => $choice]);

        // Si le panier n'existe pas ou que le statut est 100 (panier en cours)
        if (!$panier || $panier->getStatut() == 100) {
            return $this->redirectToRoute('app_commandes_client');
        }

        // On récupère les lignes du panier
        $lignes = $panier->getLigne();

        return $this->render('front/commandes_client/commande.html.twig', [
            'controller_name' => 'CommandeUtilisateurController',
            'lignes' => $lignes,
            'panier' => $panier,
        ]);
    }

}
