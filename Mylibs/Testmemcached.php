<?php

namespace Mylibs;

use \Memcached;
use \Exception;
use Mylibs\TestHealthInterface;

class TestMemcached implements TestHealthInterface
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
        $port = $config['port'] ?? 11211;

        $this->connectDb($host, $port);
    }

    protected function connectDb($host, $port)
    {
        try {
            $mc = new Memcached();

            $mc->addServer($host, $port);

            $this->timing($mc);
        } catch (Exception $e) {
            $this->error = $e->getMessage();
        }
    }

    protected function timing($mc)
    {
        try {
            $setTest = "setTest" . time();

            $timeDb = time();

            $mc->set($setTest, "memcached is working,");

            $getTest = $mc->get($setTest);

            if (empty($getTest)) {
                $this->error = "memcached isn`t working";

                return;
            }

            $timeDb = time() - $timeDb;

            if (!empty($this->timeout) && $this->timeout < $timeDb) {
                $this->error = 'error by timeout';
            } else {
                $this->status = $getTest . " timeout = " . $timeDb;
            }
        } catch (Exception $e) {
            $this->error = $e->getMessage();
        }
    }
}
