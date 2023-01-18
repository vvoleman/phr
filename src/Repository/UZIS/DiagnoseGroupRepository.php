<?php

namespace App\Repository\UZIS;

use App\Entity\UZIS\DiagnoseGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DiagnoseGroup>
 *
 * @method DiagnoseGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method DiagnoseGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method DiagnoseGroup[]    findAll()
 * @method DiagnoseGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DiagnoseGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DiagnoseGroup::class);
    }

    public function save(DiagnoseGroup $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(DiagnoseGroup $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return DiagnoseGroup[] Returns an array of DiagnoseGroup objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DiagnoseGroup
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
