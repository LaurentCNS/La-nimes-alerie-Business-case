<?php

namespace App\Controller\front;

use App\Entity\Ligne;
use App\Entity\Panier;
use App\Repository\ClientRepository;
use App\Repository\PanierRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/ajax')]
class AjaxController extends AbstractController
{

    // Déclaration pour la session
    public static string $CART = 'CART';
    public static string $QTY = 'QTY';
    public static string $TOTALPRICE = 'TOTALPRICE';
    public static string $CHOICEPAY = 'CHOICEPAY';

    #[Route('/addItemToCart/{datas}', name: 'ajax_add_item_to_cart')]
    public function index(
        Request                $request,
        SessionInterface       $session,
        EntityManagerInterface $entityManager,
        PanierRepository       $panierRepository,
    ): Response
    {

        // si l'utilisateur est connecté
        if ($this->getUser()) {
            // Si le panier en cours (100) de cet utilisateur n'existe pas dans la bdd (avant le premier ajout)
            if (!$panierRepository->findOneBy(['client' => $session->get('user'), 'statut' => 100])) {

                //CREATION DU PANIER (100) EN BDD POUR L'UTILISATEUR
                // On crée en premier le panier
                $panier = new Panier();
                // On assigne l'utilisateur au panier
                $client = $this->getUser();
                $panier->setClient($client);
                // On met la date du jour france au panier
                $panier->setDateCreation(new \DateTime('now', new \DateTimeZone('Europe/Paris')));
                // On persiste le panier
                $entityManager->persist($panier);
                // On commit le panier
                $entityManager->flush();
            }
        }

        // On récupère les données envoyées en fetch dans le fichier ts
        $datas = json_decode($request->get('datas'), true);
        // On crée un tableau vide pour stocker les données du produit dans la session
        $currentSession = [];
        // Si la session existe déjà (à partir du deuxième produit ajouté au panier)
        if ($session->has(self::$CART)) {
            // On récupère les données de la session
            $currentSession = $session->get(self::$CART);
        }
        // Si le produit n'existe pas dans la session
        if (!isset($currentSession[$datas['produitId']])) {
            // On ajoute le produit et la quantité dans le tableau
            $currentSession[$datas['produitId']] = $datas['qty'];
        } else {
            // Sinon on ajoute la quantité au produit existant
            $currentSession[$datas['produitId']] += $datas['qty'];
        }
        // On set le tableau CART dans la session
        $session->set(self::$CART, $currentSession);
        // On crée une variable pour stocker la quantité totale
        $qtyTotal = 0;
        // On parcourt le tableau pour récupérer la quantité totale
        foreach ($currentSession as $item) {
            $qtyTotal += $item;
        }
        // On enregistre la quantité totale dans la session
        $session->set(self::$QTY, $qtyTotal);
        // On retourne vers le ts un json avec la quantité totale pour l'affichage dans le panier
        return new JsonResponse(['qtyTotale' => $qtyTotal]);

    }

    /**
     * @throws Exception
     */


    #[Route('/addToFavorite/{datas}', name: 'ajax_add_item_to_favorite')]
    public function addToFavorite(
        Request                $request,
        ProduitRepository      $produitRepository,
        ClientRepository       $clientRepository,
        EntityManagerInterface $em,
        SessionInterface       $session,
    ): Response
    {

        // On récupère les données envoyées en fetch dans le fichier ts
        $datas = json_decode($request->get('datas'), true);

        // On récupère le produit
        $produit = $produitRepository->findOneBy(['id' => $datas['produitId']]);

        // On récupère l'utilisateur connecté
        $client = $clientRepository->findOneBy(['id' => $session->get('user')]);;

        // Si l'utilisateur n'a pas de favoris
        if (!$client->getProduits()->contains($produit)) {
            // On ajoute le produit dans les favoris
            $isAdded = $client->addProduit($produit);
            $em->persist($client);
            $em->flush();
            return new JsonResponse(['addOk' => $isAdded]);
        } else {
            // On supprime le produit des favoris
            $isRemoved = $client->removeProduit($produit);
            $em->persist($client);
            $em->flush();
            return new JsonResponse([]);
        }
    }

    #[Route('/totalPrice/{datas}', name: 'ajax_total_price')]
    public function totalPrice(
        Request          $request,
        SessionInterface $session,
    ): Response
    {
        // On récupère les données envoyées en fetch dans le fichier ts
        $datas = json_decode($request->get('datas'), true);

        // Si le prix total existe déjà dans la session
        if ($session->has(self::$TOTALPRICE)) {
            // On retire le prix total de la session
            $session->remove(self::$TOTALPRICE);
        }

        // On enregistre le prix total dans la session en float
        $session->set(self::$TOTALPRICE, (float)$datas['totalPrice']);

        return new JsonResponse([]);
    }

    #[Route('/choicePay/{datas}', name: 'ajax_choice_pay')]
    public function choicePay(
        Request          $request,
        SessionInterface $session,
    ): Response
    {

        // On récupère les données envoyées en fetch dans le fichier ts
        $datas = json_decode($request->get('datas'), true);

        // Si le choix de paiement existe déjà dans la session
        if ($session->has(self::$CHOICEPAY)) {
            // On retire le choix de paiement de la session
            $session->remove(self::$CHOICEPAY);
        }

        // On enregistre le choix de paiement dans la session
        $session->set(self::$CHOICEPAY, $datas['choicePay']);

        return new JsonResponse([]);
    }

    #[Route('/searchItems/{datas}', name: 'ajax_filter_search', methods: ['GET'])]
    public function filterSearch(
        string            $datas,
        ProduitRepository $produitRepository,
    ): Response
    {
        $searchValue = json_decode($datas, true);

        // Si searchValue n'est pas vide
        if (!empty($searchValue)) {
            // On récupère les produits correspondant à la recherche
            $produits = $produitRepository->getProductsBySearch($searchValue);
            // Si aucun produit n'est trouvé on affiche un message
            if (count($produits) === 0) {
                return new JsonResponse(['message' => 'Aucun produit trouvé']);
            } else {
                // Sinon on retoune les produits trouvés
                return new JsonResponse(['produits' => $produits, 'message' => 'ok']);
            }
        } else {
            // Sinon je retourne une réponse pour le ts
            return new JsonResponse(['message' => 'noSearch']);
        }
    }

}

