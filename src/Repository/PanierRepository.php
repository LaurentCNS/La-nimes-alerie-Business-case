<?php

namespace App\Repository;

use App\Entity\Panier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Panier>
 *
 * @method Panier|null find($id, $lockMode = null, $lockVersion = null)
 * @method Panier|null findOneBy(array $criteria, array $orderBy = null)
 * @method Panier[]    findAll()
 * @method Panier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PanierRepository extends AbstractLanimalerieRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Panier::class);
    }

    public function add(Panier $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Panier $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Panier[] Returns an array of Panier objects
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

//    public function findOneBySomeField($value): ?Panier
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    // Montant total des commandes payées : panier avec le statut 200
    public function montantTotalVentes(?\DateTime $startDate = null, ?\DateTime $endDate = null): float
    {
        if ($startDate === null || $endDate === null) {
            $endDate = new \DateTime('now');
            $startDate = new \DateTime('2000-01-01');
        }

        return $this->createQueryBuilder('p')
            ->select('SUM(ligne.prix*ligne.quantite ) As nbTotalVentes')
            ->join('p.ligne', 'ligne')
            ->where('p.statut = 200 OR p.statut = 400 OR p.statut = 500')
            ->andWhere('p.dateCreation BETWEEN :startDate AND :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
            ->getSingleScalarResult();
    }


    // Nombre de commandes abouties (payées, préparées et expédiées)
    public function nbCommandes(?\DateTime $startDate = null, ?\DateTime $endDate = null): int
    {
        if ($startDate === null || $endDate === null) {
            $endDate = new \DateTime('now');
            $startDate = new \DateTime('2000-01-01');
        }
        return $this->createQueryBuilder('p')
            ->select('COUNT(p) As nbCommande')
            ->where('p.statut = 200 OR p.statut = 400 OR p.statut = 500')
            ->andWhere('p.dateCreation BETWEEN :startDate AND :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
            ->getSingleScalarResult();
    }


    // Nombre total de paniers : panier avec le statut creees, payees, abandonnees, preparees, expediees.
    public function nbPaniers(?\DateTime $startDate = null, ?\DateTime $endDate = null): int
    {
        if ($startDate === null || $endDate === null) {
            $endDate = new \DateTime('now');
            $startDate = new \DateTime('2000-01-01');
        }
        return $this->createQueryBuilder('p')
            ->select('COUNT(p)')
            ->where('p.statut = 100 OR p.statut = 200 OR p.statut = 300 OR p.statut = 400 OR p.statut = 500')
            ->andWhere('p.dateCreation BETWEEN :startDate AND :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
            ->getSingleScalarResult();
    }

    // Valeur du nombre de panier pour le controlleur du pourcentage de paniers non payées et abandonnées
    public function nbPanierAnbandonnés(?\DateTime $startDate = null, ?\DateTime $endDate = null): float
    {
        if ($startDate === null || $endDate === null) {
            $endDate = new \DateTime('now');
            $startDate = new \DateTime('2000-01-01');
        }
        return $this->createQueryBuilder('p')
            ->select('COUNT(p)')
            ->where('p.statut = 100 OR p.statut = 300')
            ->andWhere('p.dateCreation BETWEEN :startDate AND :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
            ->getSingleScalarResult();
    }

    // Valeur du nombre de panier pour le controlleur  conversion commandes avec le statut 100 : panier créées
    public function nbPanierCrees(?\DateTime $startDate = null, ?\DateTime $endDate = null): int
    {
        if ($startDate === null || $endDate === null) {
            $endDate = new \DateTime('now');
            $startDate = new \DateTime('2000-01-01');
        }
        return $this->createQueryBuilder('p')
            ->select('COUNT(p)')
            ->where('p.statut = 100')
            ->andWhere('p.dateCreation BETWEEN :startDate AND :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
            ->getSingleScalarResult();
    }

    // Fonction pour le back admin : liste des commandes
    public function findCommande(): int
    {
        return $this->createQueryBuilder('p')
            ->select('p')
            ->where('p.statut = 200 OR p.statut = 400 OR p.statut = 500 OR p.statut = 600')
            ->getQuery()
              ->getResult();
    }


    //Fonction pour récuperer les infos du paginator
    public function getQbAll(): QueryBuilder
    {
        $qb = parent::getQbAll();
        return $qb->select('panier','ligne','produit','client','adresse','moyenPaiement')
            ->leftJoin('panier.ligne', 'ligne')
            ->leftJoin('ligne.produit', 'produit')
            ->leftJoin('panier.client', 'client')
            ->leftJoin('panier.adresse', 'adresse')
            ->leftJoin('panier.moyenPaiement', 'moyenPaiement')
            ->where('panier.statut = 200 OR panier.statut = 400 OR panier.statut = 500 OR panier.statut = 600 OR panier.statut = 700')
            ->orderBy('panier.datePaiement', 'DESC')
            ;
    }


}
