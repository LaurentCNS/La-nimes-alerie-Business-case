<?php

namespace App\Controller\back;

use App\Entity\Panier;
use App\Form\filter\CommandeFilterType;
use App\Form\PanierType;
use App\Repository\PanierRepository;
use Knp\Component\Pager\PaginatorInterface;
use Lexik\Bundle\FormFilterBundle\Filter\FilterBuilderUpdaterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/commande')]
class AdminCommandController extends AbstractController
{
    #[Route('/', name: 'app_admin_commande_index', methods: ['GET'])]
    public function index(PanierRepository $panierRepository, Request $request, PaginatorInterface $paginator, FilterBuilderUpdaterInterface $builderUpdater): Response
    {


        $qb = $panierRepository->getQbAll();

        // Lexik Filter
        $filterForm = $this->createForm(CommandeFilterType::class, null, [
            'method' => 'GET',
        ]);

        if ($request->query->has($filterForm->getName())) {
            $filterForm->submit($request->query->all($filterForm->getName()));
            $builderUpdater->addFilterConditions($filterForm, $qb);
        }

        // Paginator
        $panier = $paginator->paginate(
            $qb,
            $request->query->getInt('page',1),18
        );

        return $this->render('back/commande/index.html.twig', [
            'paniers' => $panier,
            'filters' => $filterForm->createView(),
        ]);
    }

//    #[Route('/new', name: 'app_admin_commande_new', methods: ['GET', 'POST'])]
//    public function new(Request $request, PanierRepository $panierRepository): Response
//    {
//        $panier = new Panier();
//        $form = $this->createForm(PanierType::class, $panier);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $panierRepository->add($panier, true);
//
//            return $this->redirectToRoute('app_admin_commande_index', [], Response::HTTP_SEE_OTHER);
//        }
//
//        return $this->renderForm('back/commande/new.html.twig', [
//            'panier' => $panier,
//            'form' => $form,
//        ]);
//    }

    #[Route('/{id}', name: 'app_admin_commande_show', methods: ['GET'])]
    public function show(Panier $panier): Response
    {
        return $this->render('back/commande/show.html.twig', [
            'panier' => $panier,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_commande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Panier $panier, PanierRepository $panierRepository): Response
    {
        $form = $this->createForm(PanierType::class, $panier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $panierRepository->add($panier, true);

            return $this->redirectToRoute('app_admin_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/commande/edit.html.twig', [
            'panier' => $panier,
            'form' => $form,
        ]);
    }

//    #[Route('/{id}', name: 'app_admin_commande_delete', methods: ['POST'])]
//    public function delete(Request $request, Panier $panier, PanierRepository $panierRepository): Response
//    {
//        if ($this->isCsrfTokenValid('delete'.$panier->getId(), $request->request->get('_token'))) {
//            $panierRepository->remove($panier, true);
//        }
//
//        return $this->redirectToRoute('app_admin_commande_index', [], Response::HTTP_SEE_OTHER);
//    }
}
