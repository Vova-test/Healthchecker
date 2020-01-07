<?php

namespace Mylibs;

use \Redis;
use \RedisException;
use Mylibs\TestHealthInterface;

class TestRedis implements TestHealthInterface
{    

    public function testing(){ return "TestRedis"; }

   /* protected function testRedis($parameters)
    {
        try {
            $status = 'Redis status = ';
            $redis = new Redis();
            $redis->pconnect(
                $parameters['credentials']['host'] ?? "localhost",
                $parameters['credentials']['port'] ?? 6379
            );

            $this->status[] = $status . ($redis->ping() ? "TRUE" : "FALSE");
        } catch (RedisException $e) {
            $this->errors[] = $e->getMessage();
        }
    }*/
}
