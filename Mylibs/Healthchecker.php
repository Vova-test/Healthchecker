<?php

namespace Mylibs;

use Mylibs\TestHealthInterface;
use Mylibs\TestService;
use Mylibs\TestMySQL;
use Mylibs\TestPostgreSQL;
use Mylibs\TestMemcached;
use Mylibs\TestRedis;
use Mylibs\TestFreeSpace;
use Mylibs\TestWritableFolder;

class HealthChecker
{
    protected $parameters = [];
    protected $errors = [];
    protected $status = [];
    protected $type = [
        'mysql' => 'MySQL',
        'pgsql' => 'PostgreSQL',
        'memcached' => 'Memcached',
        'redis' => 'Redis',
        'free_space' => 'FreeSpace',
        'folder_is_writable' => 'WritableFolder'
    ];

    public function add($parameters = [])
    {
        $this->parameters[] = $parameters;
    }

    public function test()
    {
        $testService = new TestService;
        $a = 1;
        foreach ($this->parameters as $test) {
            if (empty($test)) {
                $this->errors[] = 'Parameters are not fuond!';
                continue;
            }

            if (empty($test['type']) || !array_key_exists(strtolower($test['type']), $this->type)) {
                $this->errors[] = 'Type =' . $test['type'] ?? '' . ' unknown';
                continue;
            }
            $obj = "Mylibs\\Test" . $this->type[$test['type']];

            $objectTest = new $obj();

            $err = $testService->run($objectTest,$test);
            if (is_array($err)) {
                $this->errors[] = $err;
            }else{
                $this->status[] = $err;  
            }
            $a++;
            if ($a > 2) {
                break;
            }
        }
        return empty($this->errors) ? true : $this->errors;
    }

    /*public function page()
    {
        $this->status = [];
        foreach ($this->parameters as $test) {
            $this->{"test" . $this->type[$test['type']]}($test);
        }
        $data = $this->status;
        require_once(dirname(__FILE__) . "/page.php");
    }*/
}
