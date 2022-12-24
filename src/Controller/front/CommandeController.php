<?php

namespace App\Controller\front;

use App\Entity\Adresse;
use App\Entity\Client;
use App\Entity\MoyenPaiement;
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
        $idClient = $clientSession->getId();
        $client = $entityManager->getRepository(Client::class)->find($idClient);
        $idAdresse = $adresse->getId();
        $adresse = $entityManager->getRepository(Adresse::class)->find($idAdresse);

        if($moyenPaiement == 'cb') {
            $idMoyenPaiement = 'Carte Bancaire';
        } else {
            $idMoyenPaiement = 'Paypal';
        };
        // On récupère le moyen de paiement par son type
        $moyenPaiement = $entityManager->getRepository(MoyenPaiement::class)->findOneBy(['type' => $idMoyenPaiement]);
        $dateCreation = new \DateTime();
        $statut = 200;
        $datePaiement = new \DateTime();
        $numeroCommande = rand(1,100000);
        $montant = $totalPrice;

        // On enregistre les infos de la commande
        $commande = new Panier();
        $commande = $commande->setClient($client);
        $commande->setAdresse($adresse);
        $commande->setMoyenPaiement($moyenPaiement);
        $commande->setDateCreation($dateCreation);
        $commande->setStatut($statut);
        $commande->setDatePaiement($datePaiement);
        $commande->setNumeroCommande($numeroCommande);
        $commande->setMontantTotal($montant);

        $entityManager->persist($commande);
        $entityManager->flush();

        // On vide la session sauf l'utilisateur
        $session->remove('CART');
        $session->remove('adresseLivraison');
        $session->remove('CHOICEPAY');
        $session->remove('TOTALPRICE');
        $session->remove('QTY');


        return $this->render('front/commande/index.html.twig', [
            'controller_name' => 'CommandeController',
            'adresse' => $adresse,
        ]);
    }
}
