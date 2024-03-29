<?php

namespace App\Repository;

use App\Entity\Categorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Categorie>
 *
 * @method Categorie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Categorie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Categorie[]    findAll()
 * @method Categorie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategorieRepository extends AbstractLanimalerieRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Categorie::class);
    }

    public function add(Categorie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Categorie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Categorie[] Returns an array of Categorie objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Categorie
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    //Fonction pour récupérer les catégories
    public function findCategorie(): array
    {
        return $this->createQueryBuilder('c')
            ->Where('c.parent is null')
            ->getQuery()
            ->getResult()
        ;
    }

    //Fonction pour récupérer les sous-catégories
    public function findSousCategorie(): array
    {
        return $this->createQueryBuilder('c')
            ->Where('c.parent is not null')
            ->getQuery()
            ->getResult()
        ;
    }

    //Fonction pour récuperer les infos du paginator
    public function getQbAll(): QueryBuilder
    {
        $qb = parent::getQbAll();
        return $qb->select('categorie','animal')
            ->leftJoin('categorie.animal', 'animal')
            ->orderBy('categorie.nom', 'ASC')
        ;
    }

    //Fonction pour récupérer les catégories par animal
    public function findCategorieByAnimal($animal): array
    {
        return $this->createQueryBuilder('c')
            ->Where('c.animal = :animal')
            ->AndWhere('c.parent is null')
            ->setParameter('animal', $animal)
            ->getQuery()
            ->getResult()
        ;
    }


}
