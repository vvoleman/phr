<?php

namespace App\Repository\UZIS;

use App\Entity\UZIS\Diagnose;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Diagnose>
 *
 * @method Diagnose|null find($id, $lockMode = null, $lockVersion = null)
 * @method Diagnose|null findOneBy(array $criteria, array $orderBy = null)
 * @method Diagnose[]    findAll()
 * @method Diagnose[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DiagnoseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Diagnose::class);
    }

	public function search(string $query, int $page, string $orderBy, string $direction, int $amount = 10): array
	{
		// Get diagnoses where id or name is like query
		// Paginate
		// Return array of diagnoses
		return $this->createQueryBuilder('d')
			->where('d.id LIKE :query')
			->orWhere('d.name LIKE :query')
			->orderBy('d.'.$orderBy, $direction)
			->setParameter('query', '%' . $query . '%')
			->setFirstResult($page * $amount)
			->setMaxResults($amount)
			->getQuery()
			->getResult();
	}

    public function save(Diagnose $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Diagnose $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Diagnose[] Returns an array of Diagnose objects
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

//    public function findOneBySomeField($value): ?Diagnose
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
