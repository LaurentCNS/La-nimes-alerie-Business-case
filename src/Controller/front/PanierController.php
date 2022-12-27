<?php

namespace App\Controller\front;

use App\Repository\ClientRepository;
use App\Repository\PanierRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/panier')]
class PanierController extends AbstractController
{

    #[Route('/', name: 'app_panier')]
    public function index(SessionInterface $session, ProduitRepository $produitRepository, PanierRepository $panierRepository, ClientRepository $client, EntityManagerInterface $entityManager): Response
    {

        // créer un panier vide
        $datasWithProduct = [];

        // si le panier existe dans la session
        if ($session->has(AjaxController::$CART)) {

            // on récupère les données CART de la session
            $cartSession = $session->get(AjaxController::$CART);

            // On boucle sur les données
            foreach ($cartSession as $produitId => $qty) {
                // On déclare un tableau associatif pour stocker les données de chaque produit
                $datasWithProduct[] = [
                    'produit' => $produitRepository->find($produitId),
                    'qty' => $qty
                ];
            }
        }

        $selector = "panier";

        return $this->render('front/panier/index.html.twig', [
            'controller_name' => 'PanierController',
            'selector' => $selector,
            'produitSession' => $datasWithProduct
        ]);
    }

    // La quantité d'un produit stockée dans la session pour le delete
    public static string $QTY = 'QTY';

    #[Route('/delete', name: 'app_delete_cart')]
    public function delete(
        SessionInterface       $session,
        PanierRepository       $panierRepository,
        EntityManagerInterface $entityManager,
    ): Response
    {

        // Passe la quantité totale à 0 pour la session
        $session->set(self::$QTY, 0);

        // Supprime la session
        $session->remove(AjaxController::$CART);

        return $this->redirectToRoute('app_panier', [
        ]);

    }

    public static string $CART = 'CART';

    #[Route('/deleteItem/{id}', name: 'app_delete_Item')]
    public function deleteItem(
        SessionInterface $session, $id, PanierRepository $panierRepository, EntityManagerInterface $entityManager
    ): Response
    {

        // On récupère le panier
        $cartSession = $session->get(AjaxController::$CART);
        // On vérifie si le produit existe dans la session
        if (!empty($cartSession[$id])) {
            // On supprime le produit de la session
            unset($cartSession[$id]);
            // On met à jour la session
            $session->set(AjaxController::$CART, $cartSession);
        }

        // On récupère la quantité totale des produits
        $currentSession = [];
        $currentSession = $session->get(self::$CART);

        $qtyTotal = 0;
        // On parcourt le tableau pour récupérer la quantité totale
        foreach ($currentSession as $item) {
            $qtyTotal += $item;
        }

        // On set la quantité du produit supprimé
        $session->set(self::$QTY, $qtyTotal);

        return $this->redirectToRoute('app_panier', [
        ]);

    }
}

