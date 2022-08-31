<?php

namespace App\Controller\front;

use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/panier')]

class PanierController extends AbstractController
{

    #[Route('/', name: 'app_panier')]
    public function index(SessionInterface $session, ProduitRepository $produitRepository): Response
    {

        // créer un panier vide
        $cartDatasHtml = [];

        // si le panier existe dans la session
        if ($session->has(AjaxController::$CART)) {
            // on récupère les données du panier
            $cartSession = $session->get(AjaxController::$CART);
            // persist + flush (si nécessaire)

            // pour chaque produit du panier on récupère les données du produit et on assigne en plus la valeur de la quantité
            foreach ($cartSession as $key => $qty) {
                $cartDatasHtml[] = [
                    // on récupère le produit
                    'produit' => $produitRepository->findOneBy(['id' => $key]),
                    // on récupère la quantité
                    'qty' => $qty
                ];
            }
        }

        return $this->render('front/panier/index.html.twig', [
            'controller_name' => 'PanierController',
            'produitSession' => $cartDatasHtml
        ]);
    }

    // La quantité d'un produit est stockée dans la session pour le delete
    public static string $QTY = 'QTY';

    #[Route('/delete', name: 'app_delete_cart')]
    public function delete(
        SessionInterface $session,
    ): Response
    {

        // Passe la quantité totale à 0 pour le panier
        $session->set(self::$QTY, 0);

        // Supprime la session
        $session->remove(AjaxController::$CART);

        return $this->redirectToRoute('app_home',[
        ]);

    }

}
