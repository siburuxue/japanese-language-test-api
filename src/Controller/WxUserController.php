<?php

namespace App\Controller;

use App\Lib\Constant\Paper;
use App\Lib\Constant\Text;
use App\Service\WxUserAnswerService;
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

        // 验证
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

    public function answer(Request $request, WxUserAnswerService $wxUserAnswerService): Response
    {
        $data = $request->request->all();
        $type = $data['type'];
        $answerTime = $data['answerTime'];
        // 验证
        if(empty($data['wxUserId'])){
            return $this->error("wxUserId 不能为空");
        }
        if(empty($data['paperId'])){
            return $this->error("paperId 不能为空");
        }
        if(!empty($type) && !in_array($type, Paper::TYPE)){
            $msg = "type 类型必须为 ". implode(", ", Paper::TYPE). " 之一";
            return $this->error($msg);
        }
        if(empty($data['answer'])){
            return $this->error("answer 不能为空");
        }
        if(!is_array($data['answer'])){
            return $this->error("answer 必须为json格式");
        }

        if(empty($answerTime)){
            return $this->error("答题时长 不能为空");
        }
        if(!empty($type) && !is_numeric($answerTime)){
            return $this->error("当答题类型不为空的时候，答题时长必须为秒为单位的数字");
        }
        if(empty($type) && !is_array($answerTime)){
            return $this->error("当答题类型为空的时候，答题时长必须为答题类型为key的关联数组");
        }

        $wxUserAnswerService->answer($data);
        return $this->success();
    }
}