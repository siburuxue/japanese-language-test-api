<?php

namespace App\Repository;

use App\Entity\Dict;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Dict>
 *
 * @method Dict|null find($id, $lockMode = null, $lockVersion = null)
 * @method Dict|null findOneBy(array $criteria, array $orderBy = null)
 * @method Dict[]    findAll()
 * @method Dict[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DictRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dict::class);
    }

//    /**
//     * @return Dict[] Returns an array of Dict objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Dict
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
