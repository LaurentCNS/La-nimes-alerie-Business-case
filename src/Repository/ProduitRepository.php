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

    // Retourner les produits produits les plus vendus par ordre décroissant avec le panier en statut 200 (payé)
    public function findProduitsPlusVendus(?\DateTime $startDate = null, ?\DateTime $endDate = null): array
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
            ->orderBy('quantite', 'DESC');
        return $qb->getQuery()->getResult();
    }

    //Fonction pour récuperer les infos du paginator
    public function getQbAll(): QueryBuilder
    {
        $qb = parent::getQbAll();
        return $qb->select('produit','promotion', 'marque', 'categorie','photo')
            ->leftJoin('produit.promotion', 'promotion')
            ->leftJoin('produit.marque', 'marque')
            ->leftJoin('produit.categorie', 'categorie')
            ->leftJoin('produit.photo', 'photo')
            ->orderBy('produit.libelle', 'ASC')
            ;
    }

    //Recuperer les meilleurs produits qui ont une note egale à 5 pour la page home
    public function getBestProducts(): array
    {
        $qb = $this->createQueryBuilder('produit')
            ->select('produit','promotion', 'marque', 'categorie','photo', 'avis')
            ->leftJoin('produit.promotion', 'promotion')
            ->leftJoin('produit.marque', 'marque')
            ->leftJoin('produit.categorie', 'categorie')
            ->leftJoin('produit.photo', 'photo')
            ->leftJoin('produit.avis', 'avis')
            ->where('photo.estPrincipale = 1')
            ->andWhere('avis.note = 5' )
            ->orderBy('produit.dateEntree', 'ASC')
            ;
        return $qb->getQuery()->getResult();
    }

    //Recuperer un produit avec un slug en parametre pour la page produit (avec photo principale)
    public function getProductBySlug(string $slug): ?Produit
    {
        $qb = $this->createQueryBuilder('produit')
            ->select('produit','promotion', 'marque', 'categorie','photo','animal')
            ->leftJoin('produit.promotion', 'promotion')
            ->leftJoin('produit.marque', 'marque')
            ->leftJoin('produit.categorie', 'categorie')
            ->leftJoin('produit.photo', 'photo')
            ->leftJoin('categorie.animal', 'animal')
            ->where('produit.slug = :slug')
            ->setParameter('slug', $slug)
            ;
        return $qb->getQuery()->getOneOrNullResult();
    }




}
