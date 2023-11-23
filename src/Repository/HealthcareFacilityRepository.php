<?php

namespace App\Repository;

use App\Entity\NRPZS\HealthcareFacility;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<HealthcareFacility>
 *
 * @method HealthcareFacility|null find($id, $lockMode = null, $lockVersion = null)
 * @method HealthcareFacility|null findOneBy(array $criteria, array $orderBy = null)
 * @method HealthcareFacility[]    findAll()
 * @method HealthcareFacility[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HealthcareFacilityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HealthcareFacility::class);
    }

    public function save(HealthcareFacility $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(HealthcareFacility $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return HealthcareFacility[] Returns an array of HealthcareFacility objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('h.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?HealthcareFacility
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
