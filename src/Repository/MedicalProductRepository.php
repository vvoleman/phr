<?php

namespace App\Repository;

use App\Entity\MedicalProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MedicalProduct>
 *
 * @method MedicalProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method MedicalProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method MedicalProduct[]    findAll()
 * @method MedicalProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MedicalProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MedicalProduct::class);
    }

    public function save(MedicalProduct $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(MedicalProduct $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return MedicalProduct[] Returns an array of MedicalProduct objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?MedicalProduct
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
