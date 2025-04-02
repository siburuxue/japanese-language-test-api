<?php

namespace App\Controller;

use App\Lib\Constant\Text;
use App\Service\WxUserService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class WxUserController extends CommonController
{
    public function login(Request $request, WxUserService $loginService): Response
    {
        $code = $request->request->get('code');
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
        $rs = $loginService->login($data);
        if($rs instanceof \Exception){
            return $this->error($rs->getMessage());
        }else{
            return $this->success(Text::LOGIN_SUCCESS, $rs);
        }
    }
}