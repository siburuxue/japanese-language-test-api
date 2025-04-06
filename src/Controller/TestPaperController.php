<?php

namespace App\Controller;

use App\Lib\Constant\Paper;
use App\Service\TestPaperService;
use App\Service\WxUserAnswerService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TestPaperController extends CommonController
{
    public function list(Request $request, TestPaperService $testPaperService): Response
    {
        $page = $request->query->get('page', 1);
        $limit = $request->query->get('limit', 10);
        $rs = $testPaperService->list((int)$page, (int)$limit);
        return $this->success(data: $rs);
    }

    public function info(Request $request, TestPaperService $testPaperService): Response
    {
        $id = $request->query->get('id', 0);
        $type = $request->query->get('type', '');

        // 验证
        if (empty($id)) {
            return $this->error("id 不能为空");
        }
        if (!empty($type) && !in_array($type, Paper::TYPE)) {
            $msg = "type 类型必须为 " . implode(", ", Paper::TYPE) . " 之一";
            return $this->error($msg);
        }

        $rs = $testPaperService->info((int)$id, $type);
        return $this->success(data: $rs);
    }

    public function userAnswer(Request $request, WxUserAnswerService $wxUserAnswerService): Response
    {
        $wxUserId = $request->query->get('wxUserId', 0);
        $paperId = $request->query->get('paperId', 0);
        $type = $request->query->get('type', '');
        $rs = $wxUserAnswerService->getAnswer((int)$wxUserId, (int)$paperId, $type);
        return $this->success(data: $rs);
    }
}