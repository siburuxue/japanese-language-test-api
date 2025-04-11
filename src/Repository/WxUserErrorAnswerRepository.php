<?php

namespace App\Repository;

use App\Entity\WxUserErrorAnswer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WxUserErrorAnswer>
 *
 * @method WxUserErrorAnswer|null find($id, $lockMode = null, $lockVersion = null)
 * @method WxUserErrorAnswer|null findOneBy(array $criteria, array $orderBy = null)
 * @method WxUserErrorAnswer[]    findAll()
 * @method WxUserErrorAnswer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WxUserErrorAnswerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WxUserErrorAnswer::class);
    }

//    /**
//     * @return WxUserErrorAnswer[] Returns an array of WxUserErrorAnswer objects
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

//    public function findOneBySomeField($value): ?WxUserErrorAnswer
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
