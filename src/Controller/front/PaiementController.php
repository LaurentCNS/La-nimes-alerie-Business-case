<?php

namespace App\Controller\front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/paiement')]
class PaiementController extends AbstractController
{
    #[Route('/', name: 'app_paiement')]
    public function index(SessionInterface $session,Request $request,): Response
    {
        $selector = 'paiementOff';

        // On récupère le montant total du panier dans la session
        $totalPrice = $session->get(AjaxController::$TOTALPRICE);

        // si il existe un moyen de paiement dans la session on le supprime
        if ($session->has(AjaxController::$CHOICEPAY)) {
            $session->remove(AjaxController::$CHOICEPAY);
        }


        return $this->render('/front/paiement/index.html.twig', [
            'totalPrice' => $totalPrice,
            'controller_name' => 'PaiementController',
            'selector' => $selector,
        ]);
    }

    #[Route('/payer', name: 'app_payer')]
    public function payer(SessionInterface $session,Request $request,): Response
    {

        $selector = 'paiementOff';

        // On récupère le montant total du panier dans la session
        $totalPrice = $session->get(AjaxController::$TOTALPRICE);

        // On récupère le choix de paiement dans la session pour le stocker
        $choicePay = $session->get(AjaxController::$CHOICEPAY);

        // SI le choix de paiement n'est pas vide je set le selecteur
        if ($choicePay) {
            $selector = 'paiement';
        }

        dump($choicePay);


        return $this->render('/front/paiement/index.html.twig', [
            'totalPrice' => $totalPrice,
            'choicePay' => $choicePay,
            'selector' => $selector,
            'controller_name' => 'PaiementController',
        ]);
    }


    #[Route('/commande-confirmee/', name: 'app_confirmee')]
    public function recapitulatif($choice, SessionInterface $session,Request $request,): Response
    {





        return $this->render('/front/paiement/index.html.twig', [
            'controller_name' => 'PaiementController',
        ]);
    }

}


