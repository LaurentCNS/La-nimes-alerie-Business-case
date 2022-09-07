<?php

namespace App\Controller\front;

use App\Entity\Client;
use App\Entity\Panier;
use App\Form\RegisterType;
use App\Security\UtilisateurAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegisterController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function index(SessionInterface $session,Request $request,UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, UtilisateurAuthenticator $authenticator, EntityManagerInterface $entityManager ): Response
    {

        $client = new Client();
        $form = $this->createForm(RegisterType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $client->setPassword(
                $userPasswordHasher->hashPassword(
                    $client,
                    $form->get('password')->getData()
                )
            );


            $client->setRoles(['ROLE_USER']);
            $client->setDateInscription(new \DateTime('now'));

            // si la session contient un panier
            if ($session->has(AjaxController::$CART )) {
                // On crée en premier le panier
                $panier = new Panier();
                // On assigne l'utilisateur au panier
                $panier->setClient($client);
                $panier->setDateCreation(new \DateTime('now', new \DateTimeZone('Europe/Paris')));
                $entityManager->persist($panier);
            }

            $entityManager->persist($client);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $userAuthenticator->authenticateUser(
                $client,
                $authenticator,
                $request
            );

        }

        return $this->render('front/register/index.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
