<?php

namespace Mylibs;

use \Redis;
use \RedisException;
use Mylibs\TestHealthInterface;

class TestRedis implements TestHealthInterface
{
    protected $error;
    protected $status;
    protected $timeout;

    public function testing($data = [], $page = false)
    {
        $this->error = '';
        $this->timeout = $data['timeout'] ?? 0;

        $this->validDsnConfig($data['credentials']);

        if ($page) {
            return $this->status ?? $this->error;
        } else {
            return $this->error;
        }
    }

    protected function validDsnConfig($config = [])
    {
        $host = $config['host'] ?? "localhost";
        $port = $config['port'] ?? 6379;

        $this->connectDb($host, $port);
    }

    protected function connectDb($host, $port)
    {
        try {
            $redis = new Redis();

            $redis->pconnect($host, $port);

            $this->timing($redis);
        } catch (RedisException $e) {
            $this->error = $e->getMessage();
        }
    }

    protected function timing($redis)
    {
        try {
            $setTest = "setTest" . time();

            $timeDb = time();

            if (!$redis->ping()) {
                $this->error = "redis isn`t working";

                return;
            }

            $timeDb = time() - $timeDb;

            if (!empty($this->timeout) && $this->timeout < $timeDb) {
                $this->error = 'error by timeout';
            } else {
                $this->status = "redis is working, timeout = " . $timeDb;
            }
        } catch (RedisException $e) {
            $this->error = $e->getMessage();
        }
    }
}
