<?php

namespace App\Controller\front;

use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, SessionInterface $session, EntityManagerInterface $entityManager): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        // declarer un nouveau client
        $user = new Client();

        //si le client est connecté on récupère toutes les infos
        if($this->getUser()){
            $user = $this->getUser();
            // On démarre la session
            $session->start();
            // On enregistre les infos dans la session
            $session->set('user', $user);


//            // Si le panier existe dans la session
//            if ($session->has(AjaxController::$CART)) {
//                // On récupère les données du panier
//                $cartSession = $session->get(AjaxController::$CART);
//                // On assigne et persiste les données en boucle dans la table ligne
//                foreach ($cartSession as $produitId => $qty) {
//                // On assigne l'id de l'utilisateur au panier
//                $session->get('panier')->getClient($user);
//                }
//                // On persiste le panier
//
//            //}
        }

        return $this->render('front/security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error, 'user' => $user]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
