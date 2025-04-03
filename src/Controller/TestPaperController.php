<?php

namespace App\Controller;

use App\Service\TestPaperService;
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
}