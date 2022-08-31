<?php

namespace App\Controller\front;

use App\Entity\Panier;
use App\Entity\Ligne;
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

    public static string $CART = 'CART';
    public static string $QTY = 'QTY';

    #[Route('/addItemToCart/{datas}', name: 'ajax_add_item_to_cart')]
    public function index(
        Request $request,
        SessionInterface $session,
        EntityManagerInterface $em,
        ProduitRepository $produitRepository,
    ): Response
    {
//        $currentSession = [];
//        $cart = null;
//        if (!$session->has(self::$CART)) {
//            $cart = new Panier();
//            $em->persist($cart);
//        } else {
//            $cart = $session->get(self::$CART);
//        }
//
//
//        $datas = json_decode($request->get('datas'), true);
//
//        $product = $produitRepository->findOneBy(['id' => 4]);
////        $datas = json_decode($request->get('datas'), true);
//        if (!isset($currentSession[$datas['produitId']])) {
//              $lineProduct = $currentSession[$datas['produitId']];
//              $lineProduct->setQuantity($lineProduct->getQuantity() + $datas['qty']);
//         } else {
//              $datas = new Ligne();
//              $datas->setProduit($product);
//              $datas->setQuantite($datas['qty']);
//         }
        $datas = json_decode($request->get('datas'), true);
        $currentSession = [];
        if ($session->has(self::$CART)) {
            $currentSession = $session->get(self::$CART);
        }
        if (!isset($currentSession[$datas['produitId']])) {
            $currentSession[$datas['produitId']] = $datas['qty'];
        } else {
            $currentSession[$datas['produitId']] += $datas['qty'];
        }
        $session->set(self::$CART, $currentSession);

        $qtyTotal = 0;
        foreach ($currentSession as $item) {
            $qtyTotal += $item;
        }
        $session->set(self::$QTY, $qtyTotal);

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

