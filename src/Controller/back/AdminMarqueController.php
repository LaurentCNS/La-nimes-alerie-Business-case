<?php

namespace App\Controller\back;

use App\Entity\Marque;
use App\Form\filter\MarqueFilterType;
use App\Form\MarqueType;
use App\Repository\MarqueRepository;
use Knp\Component\Pager\PaginatorInterface;
use Lexik\Bundle\FormFilterBundle\Filter\FilterBuilderUpdaterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/marque')]
class AdminMarqueController extends AbstractController
{
    #[Route('/', name: 'app_admin_marque_index', methods: ['GET'])]
    public function index(MarqueRepository $marqueRepository, Request $request, PaginatorInterface $paginator, FilterBuilderUpdaterInterface $builderUpdater): Response
    {

        $qb = $marqueRepository->getQbAll();

        // Lexik Filter
        $filterForm = $this->createForm(MarqueFilterType::class, null, [
            'method' => 'GET',
        ]);

        if ($request->query->has($filterForm->getName())) {
            $filterForm->submit($request->query->all($filterForm->getName()));
            $builderUpdater->addFilterConditions($filterForm, $qb);
        }

        // Paginator
        $marques = $paginator->paginate(
            $qb,
            $request->query->getInt('page',1),18
        );


        return $this->render('back/marque/index.html.twig', [
            'marques' => $marques,
            'filters' => $filterForm->createView(),
        ]);
    }

    #[Route('/new', name: 'app_admin_marque_new', methods: ['GET', 'POST'])]
    public function new(Request $request, MarqueRepository $marqueRepository): Response
    {
        $marque = new Marque();
        $form = $this->createForm(MarqueType::class, $marque);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $marqueRepository->add($marque, true);

            return $this->redirectToRoute('app_admin_marque_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/marque/new.html.twig', [
            'marque' => $marque,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_marque_show', methods: ['GET'])]
    public function show(Marque $marque): Response
    {
        return $this->render('back/marque/show.html.twig', [
            'marque' => $marque,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_marque_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Marque $marque, MarqueRepository $marqueRepository): Response
    {
        $form = $this->createForm(MarqueType::class, $marque);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $marqueRepository->add($marque, true);

            return $this->redirectToRoute('app_admin_marque_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/marque/edit.html.twig', [
            'marque' => $marque,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_marque_delete', methods: ['POST'])]
    public function delete(Request $request, Marque $marque, MarqueRepository $marqueRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$marque->getId(), $request->request->get('_token'))) {
            try {
                $marqueRepository->remove($marque, true);
                $this->addFlash('success', 'Marque supprimée avec succès');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Impossible de supprimer cette marque car elle est utilisée par des produits');
            }
        }

        return $this->redirectToRoute('app_admin_marque_index', [], Response::HTTP_SEE_OTHER);
    }
}
