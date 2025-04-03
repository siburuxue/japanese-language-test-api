<?php

namespace App\Service;

use App\Entity\TestPaper;
use App\Lib\Constant\Code;
use App\Lib\Tool\ArrayTool;
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

    public function list(int $page, int $limit): array
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

    public function info(int $id, string $type): array
    {
        $rs = $this->testPaperRepository->findOneBy(['id' => $id, 'isDel' => Code::UN_DELETE]);
        if(!empty($rs)){
            $data = [
                'id' => $rs->getId(),
                'title' => $rs->getTitle(),
            ];
            $json = $rs->getTestPaperJson();
            if(empty($type)){
                $data['json'] = $json['output'];
            }else{
                $data['json'] = ["{$type}" => $json['output'][$type]];
            }
        }else{
            $data = ArrayTool::getEmptyAssociativeArray();
        }
        return $data;
    }
}