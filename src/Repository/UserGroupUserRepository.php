<?php

namespace App\Repository;

use App\Entity\UserGroupUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserGroupUser>
 *
 * @method UserGroupUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserGroupUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserGroupUser[]    findAll()
 * @method UserGroupUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserGroupUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserGroupUser::class);
    }

//    /**
//     * @return UserGroupUser[] Returns an array of UserGroupUser objects
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

//    public function findOneBySomeField($value): ?UserGroupUser
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
