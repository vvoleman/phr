<?php

namespace App\Repository;

use App\Entity\MedicalProduct;
use App\Entity\SubstanceValue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SubstanceValue>
 *
 * @method SubstanceValue|null find($id, $lockMode = null, $lockVersion = null)
 * @method SubstanceValue|null findOneBy(array $criteria, array $orderBy = null)
 * @method SubstanceValue[]    findAll()
 * @method SubstanceValue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubstanceValueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SubstanceValue::class);
    }

    public function save(SubstanceValue $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

	public function removeNotIn(MedicalProduct $medicalProduct, array $ids): void {
		$qb = $this->createQueryBuilder('sv');
		$qb->delete(SubstanceValue::class, 'sv')
			->where('sv.medicalProduct = :medicalProduct')
			->andWhere($qb->expr()->notIn('sv.id', ':ids'))
			->setParameter('medicalProduct', $medicalProduct)
			->setParameter('ids', $ids)
			->getQuery()
			->execute();
	}

    public function remove(SubstanceValue $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return SubstanceValue[] Returns an array of SubstanceValue objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?SubstanceValue
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
