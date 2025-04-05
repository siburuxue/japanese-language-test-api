<?php

namespace App\EventListener;

use App\Lib\Constant\Code;
use App\Middleware\Valida;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class RequestListener
{
    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $content = $request->getContent();
        if(!empty($content)){
            $content = json_decode($content,true);
            if(!empty($content)){
                $request->request->replace($content);
            }
        }
    }
}