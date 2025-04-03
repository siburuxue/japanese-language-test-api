<?php

namespace App\Repository;

use App\Entity\TestPaper;
use App\Lib\Constant\Code;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<TestPaper>
 *
 * @method TestPaper|null find($id, $lockMode = null, $lockVersion = null)
 * @method TestPaper|null findOneBy(array $criteria, array $orderBy = null)
 * @method TestPaper[]    findAll()
 * @method TestPaper[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TestPaperRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
    )
    {
        parent::__construct($registry, TestPaper::class);
    }

    public function list()
    {
        return $this->createQueryBuilder('t')->where("t.isDel = " . Code::UN_DELETE);
    }
}
