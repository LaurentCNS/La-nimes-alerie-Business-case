<?php

namespace App\Controller\back;

use App\Entity\Client;
use App\Form\ClientType;
use App\Form\filter\ClientFilterType;
use App\Repository\ClientRepository;
use Knp\Component\Pager\PaginatorInterface;
use Lexik\Bundle\FormFilterBundle\Filter\FilterBuilderUpdaterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/client')]
class AdminClientController extends AbstractController
{
    #[Route('/', name: 'app_admin_client_index', methods: ['GET'])]
    public function index(ClientRepository $clientRepository, Request $request, PaginatorInterface $paginator, FilterBuilderUpdaterInterface $builderUpdater): Response
    {
        $qb = $clientRepository->getQbAll();

        // Lexik Filter
        $filterForm = $this->createForm(ClientFilterType::class, null, [
            'method' => 'GET',
        ]);

        if ($request->query->has($filterForm->getName())) {
            $filterForm->submit($request->query->all($filterForm->getName()));
            $builderUpdater->addFilterConditions($filterForm, $qb);
        }


        // Paginator
        $clients = $paginator->paginate(
            $qb,
            $request->query->getInt('page',1),18
        );

        return $this->render('back/client/index.html.twig', [
            'clients' => $clients,
            'filters' => $filterForm->createView(),
        ]);
    }

    #[Route('/new', name: 'app_admin_client_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ClientRepository $clientRepository): Response
    {
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $clientRepository->add($client, true);

            return $this->redirectToRoute('app_admin_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('client/new.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_client_show', methods: ['GET'])]
    public function show(Client $client): Response
    {
        return $this->render('back/client/show.html.twig', [
            'client' => $client,
        ]);
    }

//    #[Route('/{id}/edit', name: 'app_admin_client_edit', methods: ['GET', 'POST'])]
//    public function edit(Request $request, Client $client, ClientRepository $clientRepository): Response
//    {
//        $form = $this->createForm(ClientType::class, $client);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $clientRepository->add($client, true);
//
//            return $this->redirectToRoute('app_admin_client_index', [], Response::HTTP_SEE_OTHER);
//        }
//
//        return $this->renderForm('client/edit.html.twig', [
//            'client' => $client,
//            'form' => $form,
//        ]);
//    }

    #[Route('/{id}', name: 'app_admin_client_delete', methods: ['POST'])]
    public function delete(Request $request, Client $client, ClientRepository $clientRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$client->getId(), $request->request->get('_token'))) {
            try {
            $clientRepository->remove($client, true);
                $this->addFlash('success', 'Le client a bien été supprimée');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Impossible de supprimer ce client');
            }
        }

        return $this->redirectToRoute('app_admin_client_index', [], Response::HTTP_SEE_OTHER);
    }
}
