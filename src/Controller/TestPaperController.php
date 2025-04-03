<?php

namespace App\Controller;

use App\Lib\Constant\Paper;
use App\Service\TestPaperService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TestPaperController extends CommonController
{
    public function list(Request $request, TestPaperService $testPaperService): Response
    {
        $page = $request->request->get('page', 1);
        $limit = $request->request->get('limit', 10);
        $rs = $testPaperService->list((int)$page, (int)$limit);
        return $this->success(data: $rs);
    }

    public function info(Request $request, TestPaperService $testPaperService): Response
    {
        $id = $request->request->get('id', 0);
        $type = $request->request->get('type', '');

        // 验证
        if(empty($id)){
            return $this->error("id 不能为空");
        }
        if(!empty($type) && !in_array($type, Paper::TYPE)){
            $msg = "type 类型必须为 ". implode(", ", Paper::TYPE). " 之一";
            return $this->error($msg);
        }

        $rs = $testPaperService->info((int)$id, $type);
        return $this->success(data: $rs);
    }
}