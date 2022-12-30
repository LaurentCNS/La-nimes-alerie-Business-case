<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Produit>
 *
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends AbstractLanimalerieRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

    public function add(Produit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Produit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Produit[] Returns an array of Produit objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Produit
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    // API PLATEFORME

    // Les produits les plus vendus par ordre décroissant avec le panier en statut 200 (payé)
    public function findProduitsBestSelling(?\DateTime $startDate = null, ?\DateTime $endDate = null): array
    {
        if ($startDate === null || $endDate === null) {
            $endDate = new \DateTime('now');
            $startDate = new \DateTime('2000-01-01');
        }
        $qb = $this->createQueryBuilder('p')
            ->select('p.id, p.libelle, p.prixHt,panier.statut, SUM(l.quantite) as quantite')
            ->leftjoin('p.ligne', 'l')
            ->leftjoin('l.panier', 'panier')
            ->where('panier.statut = 200')
            ->andWhere('panier.dateCreation BETWEEN :startDate AND :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->groupBy('p.id')
            ->orderBy('quantite', 'DESC')
            ->setMaxResults(5);
        return $qb->getQuery()->getResult();
    }

    // BACK ADMIN

    // Les produits pour la pagination
    public function getQbAll(): QueryBuilder
    {
        $qb = parent::getQbAll();
        return $qb->select('produit', 'promotion', 'marque', 'categorie', 'photo')
            ->leftJoin('produit.promotion', 'promotion')
            ->leftJoin('produit.marque', 'marque')
            ->leftJoin('produit.categorie', 'categorie')
            ->leftJoin('produit.photo', 'photo')
            ->orderBy('produit.libelle', 'ASC');
    }

    //PAGE HOME

    // Les meilleurs produits actifs avec la photo principale triés par les notes les plus hautes
    public function getBestProducts(): array
    {
        return $this->createQueryBuilder('produit')
            ->select('produit', 'promotion','photo', 'AVG(avis.note) as note')
            ->leftJoin('produit.promotion', 'promotion')
            ->join('produit.photo', 'photo')
            ->leftJoin('produit.avis', 'avis')
            ->where('photo.estPrincipale = 1')
            ->andWhere('produit.estActif = 1')
            ->groupBy('produit')
            ->orderBy('note','DESC')
            ->setMaxResults(6)
            ->getQuery()
            ->getResult();
    }

    // Les nouveaux produits, par la date la plus récente, pour la page home
    public function getNewProducts(): array
    {
        return $this->createQueryBuilder('produit')
            ->select('produit', 'promotion', 'photo', 'AVG(avis.note) as note')
            ->leftJoin('produit.promotion', 'promotion')
            ->join('produit.photo', 'photo')
            ->leftJoin('produit.avis', 'avis')
            ->where('photo.estPrincipale = 1')
            ->andWhere('produit.estActif = 1')
            ->groupBy('produit')
            ->orderBy('produit.dateEntree', 'DESC')
            ->setMaxResults(12)
            ->getQuery()
            ->getResult();


    }

    // Les produits en promotion par ordre de la plus forte promotion pour la page home
    public function getPromoProducts(): array
    {
        return $this->createQueryBuilder('produit')
            ->select('produit', 'promotion', 'photo', 'AVG(avis.note) as note')
            ->leftJoin('produit.promotion', 'promotion')
            ->join('produit.photo', 'photo')
            ->leftJoin('produit.avis', 'avis')
            ->where('photo.estPrincipale = 1')
            ->andWhere('produit.estActif = 1')
            ->andWhere('produit.promotion is not null')
            ->groupBy('produit')
            ->orderBy('promotion.pourcentage', 'DESC')
            ->setMaxResults(6)
            ->getQuery()
            ->getResult();
    }

    // PAGE PRODUIT

    // Produit sélectionné avec un slug en paramètre
    public function getProductBySlug(string $slug): ?Produit
    {
        return $this->createQueryBuilder('produit')
            ->select('produit', 'promotion', 'marque', 'categorie', 'animal', 'avis')
            ->leftJoin('produit.promotion', 'promotion')
            ->leftJoin('produit.marque', 'marque')
            ->leftJoin('produit.categorie', 'categorie')
            ->leftJoin('categorie.animal', 'animal')
            ->leftJoin('produit.avis', 'avis')
            ->where('produit.slug = :slug')
            ->andWhere('produit.estActif = 1')
            ->orderBy('avis.dateAvis', 'DESC')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getOneOrNullResult();
    }

    // Récupère les produits correspondant à la recherche
    public function getProductsBySearch(string $search): array
    {
        return $this->createQueryBuilder('produit')
            ->select('produit.libelle', 'produit.slug')
            ->andWhere('produit.estActif = 1')
            ->andWhere('produit.libelle LIKE :search')
            ->setParameter('search', '%' . $search . '%')
            ->groupBy('produit')
            ->orderBy('produit.libelle', 'ASC')
            ->getQuery()
            ->getResult();
    }


}
