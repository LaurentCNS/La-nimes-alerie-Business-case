<?php

namespace App\Controller\back;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Form\filter\CategorieFilterType;
use App\Repository\CategorieRepository;
use Knp\Component\Pager\PaginatorInterface;
use Lexik\Bundle\FormFilterBundle\Filter\FilterBuilderUpdaterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/categorie')]
class AdminCategorieController extends AbstractController
{


    #[Route('/', name: 'app_admin_categorie_index', methods: ['GET'])]
    public function index(CategorieRepository $categorieRepository,
                          Request $request,
                          PaginatorInterface $paginator,
                          FilterBuilderUpdaterInterface $builderUpdater
    ): Response
    {

        $qb = $categorieRepository->getQbAll();

        // Lexik Filter
        $filterForm = $this->createForm(CategorieFilterType::class, null, [
            'method' => 'GET',
        ]);


        if ($request->query->has($filterForm->getName())) {
            $filterForm->submit($request->query->all($filterForm->getName()));
            $builderUpdater->addFilterConditions($filterForm, $qb)
            ;
        }

        // Paginator
        $categories = $paginator->paginate(
            $qb,
            $request->query->getInt('page',1),19
        );

        // Retour dans le twig
        return $this->render('back/categorie/index.html.twig', [
            'categories' => $categories,
            'filters' => $filterForm->createView(),
        ]);
    }

    #[Route('/new', name: 'app_admin_categorie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CategorieRepository $categorieRepository): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try{
            $categorieRepository->add($categorie, true);
            $this->addFlash('success', 'La catégorie a bien été ajoutée');
            }catch (\Exception $e){
                $this->addFlash('danger', 'La catégorie n\'a pas été ajoutée');
            }

            return $this->redirectToRoute('app_admin_categorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/categorie/new.html.twig', [
            'categorie' => $categorie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_categorie_show', methods: ['GET'])]
    public function show(Categorie $categorie): Response
    {
        return $this->render('back/categorie/show.html.twig', [
            'categorie' => $categorie,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_categorie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Categorie $categorie, CategorieRepository $categorieRepository): Response
    {
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try{
            $categorieRepository->add($categorie, true);
            $this->addFlash('success', 'La catégorie a bien été modifiée');
            }catch (\Exception $e){
                $this->addFlash('danger', 'La catégorie n\'a pas été modifiée');
            }

            return $this->redirectToRoute('app_admin_categorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/categorie/edit.html.twig', [
            'categorie' => $categorie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_categorie_delete', methods: ['POST'])]
    public function delete(Request $request, Categorie $categorie, CategorieRepository $categorieRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorie->getId(), $request->request->get('_token'))) {
            // if it's a delete request the entity is removed from the repository else catch the exception
            try {
                $categorieRepository->remove($categorie, true);
                $this->addFlash('success', 'La catégorie a bien été supprimée');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Impossible de supprimer, une relation est présente avec d\'autres tables.');
            }
        }

        return $this->redirectToRoute('app_admin_categorie_index', [], Response::HTTP_SEE_OTHER);
    }
}
