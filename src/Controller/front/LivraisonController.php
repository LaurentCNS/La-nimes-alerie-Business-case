<?php

namespace App\Controller\front;

use App\Entity\Ligne;
use App\Form\AdresseClientType;
use App\Repository\PanierRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/livraison')]
class LivraisonController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em)
    {}


    #[Route('/', name: 'app_livraison')]
    public function index(SessionInterface $session, ProduitRepository $produitRepository, PanierRepository $panierRepository, EntityManagerInterface $entityManager): Response
    {

        // Si l'utilisateur n'est pas connecté, on le redirige vers la page de connexion
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_register');
        }

        // si le panier existe dans la session
        if ($session->has(AjaxController::$CART)) {

            // GESTION DU PANIER
            // Si l'utilisateur identifié a déjà un panier en cours (creer lors de l'ajout du premier produit -> AjaxController)
            if ($panierRepository->findOneBy(['client' => $session->get('user'), 'statut' => 100])) {

                // On récupère le panier
                $panier = $panierRepository->findOneBy(['client' => $session->get('user'), 'statut' => 100]);
                // On supprime les lignes du panier en premier par rapport au foreign key
                $lignes = $panier->getLigne();
                // Si le panier a des lignes

                // On supprime les lignes
                foreach ($lignes as $ligne) {
                    $entityManager->remove($ligne);
                }

                // On commit les lignes
                $entityManager->flush();

            } else {
                return $this->redirectToRoute('app_panier', [
                ]);
            }

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

            // On récupère le montant total du panier dans la session
            $totalPrice = $session->get(AjaxController::$TOTALPRICE);

            // dump de la session
            dump($session->get(AjaxController::$QTY));

            dump($totalPrice);

            // On crée un sélecteur de css
            $selector = 'livraison';

            return $this->render('front/livraison/index.html.twig', [
                'controller_name' => 'LivraisonController',
                'totalPrice' => $totalPrice,
                'selector' => $selector,
            ]);
        } else {
            // Sinon on redirige vers la page panier
            return $this->redirectToRoute('app_panier');
        }
    }


    // CHOIX DE L'ADRESSE DE LIVRAISON
    #[Route('/adresse/{choice}', name: 'app_livraison_adresse')]
    public function address(SessionInterface $session,Request $request, $choice): Response
    {
        // Si l'utilisateur n'est pas connecté, on le redirige vers la page de connexion
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_register');
        }

        // On crée un sélecteur pour le choix de l'adresse
        $selector = '';

        // Si le choix est principale
        if ($choice == 'principale') {
            // Si le client a déjà une adresse de livraison
            if ($this->getUser()->getAdressePrincipale()) {
                // On enregistre l'adresse principale
                $adressePrincipale = $this->getUser()->getAdressePrincipale();
                // On passe le choix d'adresse de livraison dans la session
                $request->getSession()->set('adresseLivraison', $adressePrincipale);
            }else{
                // Assignation d'un tableau vide temporaire pour les conditions twig
                $adressePrincipale = [];
                // On crée un message d'erreur pour l'adresse principale
                $this->addFlash('danger', 'Pas d\'adresse principale enregistrée');
            }
            // On assigne le sélecteur
            $selector = 'principale';

        } else if ($choice == 'secondaire') {
            // Si le client a déjà une adresse de livraison
            if ($this->getUser()->getAdresseSecondaire()) {
                // On enregistre l'adresse secondaire
                $adresseSecondaire = $this->getUser()->getAdresseSecondaire();
                // On passe le choix d'adresse de livraison dans la session
                $request->getSession()->set('adresseLivraison', $adresseSecondaire);
            }else{
                // Assignation d'un tableau vide temporaire pour les conditions twig
                $adresseSecondaire = [];
                // On crée un message d'erreur pour l'adresse secondaire
                $this->addFlash('danger', 'Pas d\'adresse secondaire enregistrée');
            }
            // On assigne le sélecteur
            $selector = 'secondaire';
        }

        // On récupère le montant total du panier dans la session
        $totalPrice = $session->get(AjaxController::$TOTALPRICE);

        return $this->render('front/livraison/choice.html.twig', [
            'controller_name' => 'AdresseController',
            'totalPrice' => $totalPrice,
            'selector' => $selector,
            'adressePrincipale' => $adressePrincipale ?? null,
            'adresseSecondaire' => $adresseSecondaire ?? null,
        ]);
    }


    // AJOUT OU MODIFICATION DE L'ADRESSE DE LIVRAISON
    #[Route('/ajout/adresse/{choice}', name: 'app_ajout_adresse')]
    public function addAddress(SessionInterface $session,Request $request, $choice): Response
    {
        // Si l'utilisateur n'est pas connecté, on le redirige vers la page de connexion
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_register');
        }

        // On crée un formulaire d'adresse
        $form = $this->createForm(AdresseClientType::class);

        // On crée un sélecteur pour le choix de l'adresse (css et la suite du traitement)
        $selector = '';

        // Si le choix est principale
        if ($choice == 'principale') {
            // Si l'utilisateur a déjà une adresse principale, on la récupère
            if ($this->getUser()->getAdressePrincipale()) {
                $form->setData($this->getUser()->getAdressePrincipale());
            }
            // On assigne le sélecteur
            $selector = 'principale';
        } else if ($choice == 'secondaire') {
            // Si l'utilisateur a déjà une adresse secondaire, on la récupère
            if ($this->getUser()->getAdresseSecondaire()) {
                $form->setData($this->getUser()->getAdresseSecondaire());
            }
            // On assigne le sélecteur
            $selector = 'secondaire';
        }

        $form->handleRequest($request);

        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            $datas = $form->getData();
            // Assignation de l'adresse au client suivant le sélecteur
            if ($choice == 'principale') {
                // Mettre le boolean estPrincipal à true (pour l'adresse principale)
                $datas->setEstPrincipale(true);
            } else if ($choice == 'secondaire') {
                // Mettre le boolean estPrincipal à false (pour l'adresse secondaire)
                $datas->setEstPrincipale(false);
            }
            // Rattacher l'adresse au client
            $datas->setClient($this->getUser());
            $this->em->persist($datas);
            $this->em->flush();

            return $this->redirectToRoute('app_livraison');
        }

        // On récupère le montant total du panier dans la session
        $totalPrice = $session->get(AjaxController::$TOTALPRICE);

        return $this->render('front/livraison/edit.html.twig', [
            'controller_name' => 'AdresseController',
            'form' => $form->createView(),
            'totalPrice' => $totalPrice,
            'selector' => $selector
        ]);
    }
}
