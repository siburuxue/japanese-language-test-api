<?php

namespace App\Service;

use App\Entity\WxUser;
use App\Lib\Constant\Redis;
use App\Lib\Constant\WeChat;
use App\Lib\Tools\RedisConnection;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class WxUserService extends CommonService
{
    private ObjectRepository $wxUserRepository;

    public function __construct(
        private HttpClientInterface $client,
        private ManagerRegistry $doctrine,
    ) {
        $this->wxUserRepository = $doctrine->getRepository(WxUser::class);
    }

    public function login(array $item): array|\Exception
    {
        $params = [
            'appid' => WeChat::APP_ID,
            'secret' => WeChat::APP_SECRET,
            'js_code' => $item['code'],
            'grant_type' => "authorization_code",
        ];
        $response = $this->client->request('GET', WeChat::CODE2_SESSION_URL, ['query' => $params]);
        $json = $response->toArray();
        if(isset($json['errcode'])){
            return new \Exception($json['errmsg']);
        }
        if(!isset($json['unionid'])){
            $json['unionid'] = "";
        }
        // 判断用户是否存在，如果不存在，新建用户，并设置登录状态为7天
        $wxUser = $this->wxUserRepository->findOneBy(['openId' => $json['openid']]);
        // 用户登录token
        $token = md5($json['openid']);
        if (empty($wxUser)) {
            // 如果用户不存在则注册新用户
            $data = [
                'openId' => $json['openid'],
                'unionId' => $json['unionid'],
                'token' => $token,
                'username' => $item['username'],
                'avatarUrl' => $item['avatarUrl'],
                'gender' => $item['gender'],
                'language' => $item['language'],
                'city' => $item['city'],
                'province' => $item['province'],
                'country' => $item['country'],
            ];
            $wxUserId = $this->wxUserRepository->insert($data);
        }else{
            $token = $wxUser->getToken();
            $wxUserId = $wxUser->getId();
        }
        // 如果新用户，则注册后，设置登录状态过期世间
        // 如果不是新用户，则可能是换了手机登录，因为没有用token直接判断是否过期，所以同样设置登录状态过期时间,不管这个token是否过期
        RedisConnection::init()->setex($token, Redis::EXPIRE, $wxUserId);
        return ['token' => $token, 'wxUserId' => $wxUserId];
    }


}