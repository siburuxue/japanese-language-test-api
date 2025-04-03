<?php

namespace App\Service;

use App\Entity\TestPaper;
use App\Lib\Constant\Code;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectRepository;
use Knp\Component\Pager\PaginatorInterface;

class TestPaperService
{
    private ObjectRepository $testPaperRepository;


    public function __construct(
        ManagerRegistry $doctrine,
        private PaginatorInterface $paginator,
    )
    {
        $this->testPaperRepository = $doctrine->getRepository(TestPaper::class);
    }

    public function list(int $page, int $limit)
    {
        $db = $this->testPaperRepository->list();
        $rs = $this->paginator->paginate($db, $page, $limit);
        return array_map(function ($v){
            return [
                'id' => $v->getId(),
                'title' => $v->getTitle(),
            ];
        }, $rs->getItems());
    }
}