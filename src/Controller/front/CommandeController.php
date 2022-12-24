<?php

namespace App\Controller\front;

use App\Entity\Panier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CommandeController extends AbstractController
{
    #[Route('/commande', name: 'app_commande')]
    public function index(SessionInterface $session,Request $request, EntityManagerInterface $entityManager): Response
    {

        // Si l'utilisateur n'est pas connecté, on le redirige vers la page de connexion
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_register');
        }

        // Si la session est vide, on le redirige vers la page d'accueil
        if ($session->get('CART') == null) {
            return $this->redirectToRoute('app_home');
        }

        // On enregistre la session dans un objet
        $session = $request->getSession();

        // On récupère les infos de la session
        $clientSession = $session->get('user');
        $panier = $session->get('CART');
        $adresse = $session->get('adresseLivraison');
        $moyenPaiement = $session->get('CHOICEPAY');
        $totalPrice = $session->get('TOTALPRICE');

        // Informations pour le panier
        //$idClient = $clientSession->getId();

        // get client

        $idAdresse = $adresse->getId();
        if($moyenPaiement == 'CB') {
            $idMoyenPaiement = 1;
        } else {
            $idMoyenPaiement = 2;
        };
        $dateCreation = new \DateTime();
        $statut = 200;
        $datePaiement = new \DateTime();
        $numeroCommande = rand(1,100000);
        $montant = $totalPrice;



        // On enregistre les infos de la commande
        $commande = new Panier();
        //$commande = $commande->setClient($client);
        //$commande->setAdresse($adresse);
        //$commande->setMoyenPaiement($idMoyenPaiement);
        $commande->setDateCreation($dateCreation);
        $commande->setStatut($statut);
        $commande->setDatePaiement($datePaiement);
        $commande->setNumeroCommande($numeroCommande);
        $commande->setMontantTotal($montant);

        $entityManager->persist($commande);
        $entityManager->flush();

        // On vide la session
        $session->clear();


        return $this->render('front/commande/index.html.twig', [
            'controller_name' => 'CommandeController',
            'adresse' => $adresse,
        ]);
    }
}
