<?php

namespace App\Controller;

use App\Lib\Constant\Code;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class CommonController extends AbstractController
{
    protected function error($msg = "", $data = []): JsonResponse
    {
        return $this->json([
            'status' => Code::RESPONSE_FALSE,
            'msg' => $msg,
            'data' => $data
        ]);
    }

    protected function success($msg = "", $data = []): JsonResponse
    {
        return $this->json([
            'status' => Code::RESPONSE_TRUE,
            'msg' => $msg,
            'data' => $data,
        ]);
    }
}