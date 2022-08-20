<?php

namespace App\Controller\back;

use App\Entity\Photo;
use App\Entity\Produit;
use App\Form\filter\ProduitFilterType;
use App\Form\ProduitType;
use App\Repository\PhotoRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Lexik\Bundle\FormFilterBundle\Filter\FilterBuilderUpdaterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('admin/produit')]
class AdminProduitController extends AbstractController
{

    #[Route('/', name: 'app_admin_produit_index', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository, Request $request, PaginatorInterface $paginator,FilterBuilderUpdaterInterface $builderUpdater): Response
    {
        // Si l'utilisateur n'est pas admin, on redirige vers la page d'accueil
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_home');
        }

        $qb = $produitRepository->getQbAll();

        // Lexik Filter
        $filterForm = $this->createForm(ProduitFilterType::class, null, [
            'method' => 'GET',
        ]);

        if ($request->query->has($filterForm->getName())) {
            $filterForm->submit($request->query->all($filterForm->getName()));
            $builderUpdater->addFilterConditions($filterForm, $qb);
        }


        // Paginator
        $produits = $paginator->paginate(
            $qb,
            $request->query->getInt('page',1),18
        );



        return $this->render('back/produit/index.html.twig', [
            'produits' => $produits,
            'filters' => $filterForm->createView(),
        ]);
    }

    #[Route('/new', name: 'app_admin_produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProduitRepository $produitRepository, SluggerInterface $slugger): Response
    {
        // Si l'utilisateur n'est pas admin, on redirige vers la page d'accueil
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_home');
        }

        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            try{
                // ajout de la date de création a la date et heure Fr
                $produit->setDateEntree(new \DateTime('now', new \DateTimeZone('Europe/Paris')));

                // ajout d'un slug
                $produit->setSlug($slugger->slug($produit->getLibelle()));

                //recuperation de la photo et ajout dans le dossier uploads
                $photo = $form['photoPrincipale']->getData();
                if($photo){
                    $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$photo->guessExtension();
                    $photo->move($this->getParameter('photo_directory'), $newFilename);

                }


                $produitRepository->add($produit, true);
                $this->addFlash('success', 'Produit ajouté avec succès');
            }catch(\Exception $e){
                $this->addFlash('danger', 'Erreur lors de l\'ajout du produit');
            }

            return $this->redirectToRoute('app_admin_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_produit_show', methods: ['GET'])]
    public function show(Produit $produit): Response
    {
        // Si l'utilisateur n'est pas admin, on redirige vers la page d'accueil
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_home');
        }

        return $this->render('back/produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produit $produit, ProduitRepository $produitRepository): Response
    {
        // Si l'utilisateur n'est pas admin, on redirige vers la page d'accueil
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_home');
        }

        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try{
            $produitRepository->add($produit, true);
            $this->addFlash('success', 'Produit modifié avec succès');
            }catch(\Exception $e){
                $this->addFlash('danger', 'Erreur lors de la modification du produit');
            }

            return $this->redirectToRoute('app_admin_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_produit_delete', methods: ['POST'])]
    public function delete(Request $request, Produit $produit, ProduitRepository $produitRepository): Response
    {
        // Si l'utilisateur n'est pas admin, on redirige vers la page d'accueil
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_home');
        }

        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->request->get('_token'))) {
            try{
            $produitRepository->remove($produit, true);
            $this->addFlash('success', 'Produit supprimé avec succès');
            }catch(\Exception $e){
                $this->addFlash('danger', 'Impossible de supprimer, une relation est présente avec d\'autres tables.');
            }
        }

        return $this->redirectToRoute('app_admin_produit_index', [], Response::HTTP_SEE_OTHER);
    }
}
