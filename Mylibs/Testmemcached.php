<?php

namespace Mylibs;

use \Memcached;
use Mylibs\TestHealthInterface;

class TestMemcached implements TestHealthInterface
{

    public function testing(){ return "TestMemcached"; }

    /*protected function testMemcached($parameters)
    {
        try {
            $status = 'Memcached status = ';
            $mc = new Memcached();
            $mc->addServer(
                $parameters['credentials']['host'] ?? "localhost",
                $parameters['credentials']['port'] ?? 11211
            );
            $mc->set("foo", "TRUE");
            $this->status[] = $status . (!empty($mc->get("foo")) ? "TRUE" : "FALSE");
        } catch (Exception $e) {
            $this->errors[] = $e->getMessage();
        }
    }*/
}
