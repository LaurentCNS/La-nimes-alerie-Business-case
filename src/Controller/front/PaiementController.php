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

        // Si l'utilisateur n'est pas connecté, on le redirige vers la page de connexion
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_register');
        }

        // Si la session est vide, on le redirige vers la page d'accueil
        if ($session->get('CART') == null) {
            return $this->redirectToRoute('app_home');
        }

        $selector = 'paiementOff';

        // On récupère le montant total du panier dans la session
        $totalPrice = $session->get(AjaxController::$TOTALPRICE);

        //DUMP SESSION
        //dump($session->all());

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

        // Si l'utilisateur n'est pas connecté, on le redirige vers la page de connexion
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_register');
        }

        // Si la session est vide, on le redirige vers la page d'accueil
        if ($session->get('CART') == null) {
            return $this->redirectToRoute('app_home');
        }

        $selector = 'paiementOff';

        // On récupère le montant total du panier dans la session
        $totalPrice = $session->get(AjaxController::$TOTALPRICE);

        // On récupère le choix de paiement dans la session pour le stocker
        $choicePay = $session->get(AjaxController::$CHOICEPAY);

        // SI le choix de paiement n'est pas vide je set le selecteur
        if ($choicePay) {
            $selector = 'paiement';
        }


        return $this->render('/front/paiement/index.html.twig', [
            'totalPrice' => $totalPrice,
            'choicePay' => $choicePay,
            'selector' => $selector,
            'controller_name' => 'PaiementController',
        ]);
    }

}


