<?php

namespace App\Controller\front;


use App\Form\AdresseClientType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/adresse')]
class AdresseController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,

    ) { }

    #[Route('/', name: 'app_adresse')]
    public function index(Request $request): Response
    {
        // Si l'utilisateur n'est pas connecté, on le redirige vers la page de connexion
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_register');
        }

        return $this->render('front/adresse/index.html.twig', [
            'controller_name' => 'AdresseController',
        ]);
    }

    #[Route('/principale', name: 'app_adresse_principale')]
    public function principale(Request $request): Response
    {
        // Si l'utilisateur n'est pas connecté, on le redirige vers la page de connexion
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_register');
        }

        $form = $this->createForm(AdresseClientType::class);

        // Si l'utilisateur a déjà une adresse principale, on la récupère
        if ($this->getUser()->getAdressePrincipale()) {
            $form->setData($this->getUser()->getAdressePrincipale());
        }
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            $datas = $form->getData();
            // Mettre le boolean estPrincipal à true (pour l'adresse principale)
            $datas->setEstPrincipale(true);
            // Rattacher l'adresse au client
            $datas->setClient($this->getUser());
            $this->em->persist($datas);
            $this->em->flush();

            return $this->redirectToRoute('app_home');
        }


        return $this->render('front/adresse/principale.html.twig', [
            'controller_name' => 'AdresseController',
            'form' => $form->createView()
        ]);
    }

    #[Route('/secondaire', name: 'app_adresse_secondaire')]
    public function secondaire(Request $request): Response
    {
        // Si l'utilisateur n'est pas connecté, on le redirige vers la page de connexion
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_register');
        }

        $form = $this->createForm(AdresseClientType::class);

        // Si l'utilisateur a déjà une adresse secondaire, on la récupère
        if ($this->getUser()->getAdresseSecondaire()) {
            $form->setData($this->getUser()->getAdresseSecondaire());
        }
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            $datas = $form->getData();
            // Mettre le boolean estPrincipal à false (pour l'adresse secondaire)
            $datas->setEstPrincipale(false);
            // Rattacher l'adresse au client
            $datas->setClient($this->getUser());
            $this->em->persist($datas);
            $this->em->flush();

            return $this->redirectToRoute('app_home');
        }


        return $this->render('front/adresse/secondaire.html.twig', [
            'controller_name' => 'AdresseController',
            'form' => $form->createView()
        ]);
    }
}
