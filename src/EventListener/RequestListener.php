<?php

namespace App\EventListener;

use App\Lib\Constant\Code;
use App\Lib\Constant\Redis;
use App\Lib\Constant\Text;
use App\Lib\Tool\RedisConnection;
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
        $request = $event->getRequest();
        $routeName = $request->attributes->get('_route');
        if($routeName != "wx-user-login" && $routeName != "wx-user-check-login"){
            $token = $request->headers->get("token", "");
            if(empty($token)){
                $response = new JsonResponse(['msg' => 'token不能为空'], Response::HTTP_FORBIDDEN);
                $event->setResponse($response);
            }else{
                $exist = RedisConnection::init()->exists($token);
                if(!$exist){
                    $response = new JsonResponse(['msg' => Text::LOGIN_EXPIRED], Response::HTTP_FORBIDDEN);
                    $event->setResponse($response);
                }
            }
        }
    }
}