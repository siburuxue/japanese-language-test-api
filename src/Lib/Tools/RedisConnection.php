<?php

namespace App\Lib\Tools;

class RedisConnection
{
    public static function init(){
        $redis = new \Redis();
        $redis->connect('127.0.0.1', 6379);
        $redis->auth('japanese-test');
        $redis->select(0);
        return $redis;
    }
}