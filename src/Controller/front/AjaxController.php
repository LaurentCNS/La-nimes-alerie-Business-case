<?php

namespace App\Controller\front;

use App\Entity\Ligne;
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

    #[Route('/addItemToCart/{datas}', name: 'ajax_add_item_to_cart')]
    public function index(
        Request          $request,
        SessionInterface $session,
    ): Response
    {

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


//    #[Route('/addToFavorite/{datas}', name: 'ajax_add_item_to_favorite')]
//    public function addToFavorite(
//        Request $request,
//        GameRepository $gameRepository,
//        AccountRepository $accountRepository,
//        EntityManagerInterface $em
//    ): Response
//    {
//        $datas = json_decode($request->get('datas'), true);
//        $game = $gameRepository->findOneBy(['id' => $datas['gameId']]);
//        $user = $accountRepository->findOneBy(['id' => 606]);
//        // $user = $this->getUser();
//        $isAdded = $user->addToFavorite($game);
//        $em->flush();
//        return new JsonResponse(['OK' => $isAdded]);
//    }


}

