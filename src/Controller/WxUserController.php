<?php

namespace App\Controller;

use App\Lib\Constant\Text;
use App\Service\WxUserService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class WxUserController extends CommonController
{
    public function login(Request $request, WxUserService $wxUserService): Response
    {
        $code = $request->request->get('code', '');
        $username = $request->request->get('nickName', '');
        $avatarUrl = $request->request->get('avatarUrl', '');
        $gender = $request->request->get('gender', '');
        $language = $request->request->get('language', '');
        $city = $request->request->get('city', '');
        $province = $request->request->get('province', '');
        $country = $request->request->get('country', '');

        //验证
        if(empty($code)){
            return $this->error("code 不能为空");
        }

        $data = [
            'code' => $code,
            'username' => $username,
            'avatarUrl' => $avatarUrl,
            'gender' => $gender,
            'language' => $language,
            'city' => $city,
            'province' => $province,
            'country' => $country,
        ];
        $rs = $wxUserService->login($data);
        if($rs instanceof \Exception){
            return $this->error($rs->getMessage());
        }else{
            return $this->success(Text::LOGIN_SUCCESS, $rs);
        }
    }

    public function checkLogin(Request $request, WxUserService $wxUserService): Response
    {
        $token = $request->request->get('token', '');
        if(empty($token)){
            return $this->error("token 不能为空");
        }
        $rs = $wxUserService->checkLogin($token);
        if($rs){
            return $this->success(Text::LOGIN_NOT_EXPIRED);
        }else{
            return $this->success(Text::LOGIN_EXPIRED);
        }
    }
}