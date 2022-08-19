<?php

namespace App\Controller\back;

use App\Entity\Adresse;
use App\Form\AdresseType;
use App\Form\filter\AdresseFilterType;
use App\Repository\AdresseRepository;
use Knp\Component\Pager\PaginatorInterface;
use Lexik\Bundle\FormFilterBundle\Filter\FilterBuilderUpdaterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/adresse')]
class AdminAdresseController extends AbstractController
{
    #[Route('/', name: 'app_admin_adresse_index', methods: ['GET'])]
    public function index(AdresseRepository $adresseRepository, Request $request,PaginatorInterface $paginator, FilterBuilderUpdaterInterface $builderUpdater): Response
    {
        $qb = $adresseRepository->getQbAll();

        // Lexik Filter
        $filterForm = $this->createForm(AdresseFilterType::class, null, [
            'method' => 'GET',
        ]);

        if ($request->query->has($filterForm->getName())) {
            $filterForm->submit($request->query->all($filterForm->getName()));
            $builderUpdater->addFilterConditions($filterForm, $qb);
        }


        // Paginator
        $adresses = $paginator->paginate(
            $qb,
            $request->query->getInt('page',1),19
        );

        return $this->render('back/adresse/index.html.twig', [
            'adresses' => $adresses,
            'filters' => $filterForm->createView(),
        ]);
    }


//    #[Route('/new', name: 'app_admin_adresse_new', methods: ['GET', 'POST'])]
//    public function new(Request $request, AdresseRepository $adresseRepository): Response
//    {
//        $adresse = new Adresse();
//        $form = $this->createForm(AdresseType::class, $adresse);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $adresseRepository->add($adresse, true);
//
//            return $this->redirectToRoute('app_admin_adresse_index', [], Response::HTTP_SEE_OTHER);
//        }
//
//        return $this->renderForm('adresse/new.html.twig', [
//            'adresse' => $adresse,
//            'form' => $form,
//        ]);
//    }

    #[Route('/{id}', name: 'app_admin_adresse_show', methods: ['GET'])]
    public function show(Adresse $adresse): Response
    {
        return $this->render('back/adresse/show.html.twig', [
            'adresse' => $adresse,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_adresse_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Adresse $adresse, AdresseRepository $adresseRepository): Response
    {
        $form = $this->createForm(AdresseType::class, $adresse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $adresseRepository->add($adresse, true);

            return $this->redirectToRoute('app_admin_adresse_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/adresse/edit.html.twig', [
            'adresse' => $adresse,
            'form' => $form,
        ]);
    }

//    #[Route('/{id}', name: 'app_admin_adresse_delete', methods: ['POST'])]
//    public function delete(Request $request, Adresse $adresse, AdresseRepository $adresseRepository): Response
//    {
//        if ($this->isCsrfTokenValid('delete'.$adresse->getId(), $request->request->get('_token'))) {
//            $adresseRepository->remove($adresse, true);
//        }
//
//        return $this->redirectToRoute('app_admin_adresse_index', [], Response::HTTP_SEE_OTHER);
//    }
}
