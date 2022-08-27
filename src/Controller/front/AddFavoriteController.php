<?php

namespace App\Controller\front;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddFavoriteController extends AbstractController
{
    #[Route('/add/favorite/{id}', name: 'app_add_favorite')]
    public function index($id, EntityManagerInterface $entityManager, Produit $produit): Response
    {

        return $this->redirectToRoute('app_produit');

    }
}
