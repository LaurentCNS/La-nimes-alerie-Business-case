<?php

namespace App\Controller\front;

use App\Entity\Ligne;
use App\Entity\Panier;
use App\Repository\PanierRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class LivraisonController extends AbstractController
{
    #[Route('/livraison', name: 'app_livraison')]
    public function index(SessionInterface $session, ProduitRepository $produitRepository, PanierRepository $panierRepository, EntityManagerInterface $entityManager): Response
    {


        // si le panier existe dans la session
        if ($session->has(AjaxController::$CART)) {

            // GESTION DU PANIER
            // Si l'utilisateur identifié a déjà un panier en cours
            if ($panierRepository->findOneBy(['client' => $session->get('user'), 'statut' => 100])) {

                // On récupère le panier
                $panier = $panierRepository->findOneBy(['client' => $session->get('user'), 'statut' => 100]);
                // On supprime les lignes du panier en premier par rapport au foreign key
                $lignes = $panier->getLigne();
                foreach ($lignes as $ligne) {
                    $entityManager->remove($ligne);
                    $entityManager->flush();
                }
                // On supprime ce panier
                $entityManager->remove($panier);
                $entityManager->flush();
            }

            // On crée un nouveau panier
            $panier = new Panier();
            // On assigne l'utilisateur au panier
            $client = $this->getUser();
            $client->addPanier($panier);
            // On met la date du jour france au panier
            $panier->setDateCreation(new \DateTime('now', new \DateTimeZone('Europe/Paris')));
            // On persiste le panier
            $entityManager->persist($panier);
            // On commit le panier
            $entityManager->flush();


            // GESTION DES LIGNES
            // On assigne et persiste les données avec une boucle dans la table ligne

            // on récupère les données de la session pour assigner les données aux lignes
            $cartSession = $session->get(AjaxController::$CART);
            foreach ($cartSession as $produitId => $qty) {

                // On crée une nouvelle ligne
                $ligne = new Ligne();
                // On récupère le produit et on l'assigne à la ligne
                $ligne->setProduit($produitRepository->find($produitId));
                // On assigne les données de la session à la ligne
                $ligne->setQuantite($qty);
                $ligne->setPrix($produitRepository->find($produitId)->getPrixHt());
                $ligne->setTva($produitRepository->find($produitId)->getTva());
                $ligne->setLibelle($produitRepository->find($produitId)->getLibelle());
                $ligne->setPromotion($produitRepository->find($produitId)->getPourcentagePromotion());
                // On assigne le panier à la ligne
                $ligne->setPanier($panier);
                // on persiste les lignes
                $entityManager->persist($ligne);
            }

            // On commit les lignes
            $entityManager->flush();

            return $this->render('front/livraison/index.html.twig', [
                'controller_name' => 'LivraisonController',
            ]);
        } else {
            // Sinon on redirige vers la page panier
            return $this->redirectToRoute('app_panier');
        }
    }
}
