<?php

namespace App\Controller\front;

use App\Repository\ClientRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/favoris')]
class FavorisController extends AbstractController
{
    #[Route('/', name: 'app_favoris')]
    public function index(): Response
    {
        // Si l'utilisateur n'est pas connecté, on le redirige vers la page de connexion
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_register');
        }

        // on recupere les produits favoris de l'utilisateur
        $favoris = $this->getUser()->getProduits();


        dump($favoris);
        return $this->render('front/favoris/index.html.twig', [
            'controller_name' => 'FavorisController',
            'favoris' => $favoris,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_favoris_delete')]
    public function delete(
        SessionInterface $session,
        ProduitRepository $produitRepository,
        ClientRepository $clientRepository,
        EntityManagerInterface $entityManager,
        $id
    ): Response
    {

        // on supprime le produit du favoris de l'utilisateur
        $client = $this->getUser();
        // on recupere le produit
        $produit = $produitRepository->find($id);
        $client->removeProduit($produit);
        $entityManager->persist($client);
        $entityManager->flush();

        return $this->redirectToRoute('app_favoris', [
        ]);

    }
}
