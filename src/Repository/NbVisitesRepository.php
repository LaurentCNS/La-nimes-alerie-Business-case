<?php

namespace App\Repository;

use App\Entity\NbVisites;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NbVisites>
 *
 * @method NbVisites|null find($id, $lockMode = null, $lockVersion = null)
 * @method NbVisites|null findOneBy(array $criteria, array $orderBy = null)
 * @method NbVisites[]    findAll()
 * @method NbVisites[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NbVisitesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NbVisites::class);
    }

    public function add(NbVisites $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(NbVisites $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return NbVisites[] Returns an array of NbVisites objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('n.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?NbVisites
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    // Compter le nombre de visites
    public function compteurVisite(?\DateTime $startDate = null, ?\DateTime $endDate = null): int
    {
        if ($startDate === null || $endDate === null) {
            $endDate = new \DateTime('now');
            $startDate = new \DateTime('2000-01-01');
        }
        return $this->createQueryBuilder('n')
            ->select('COUNT(n)')
            ->andWhere('n.dateVisite BETWEEN :startDate AND :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
