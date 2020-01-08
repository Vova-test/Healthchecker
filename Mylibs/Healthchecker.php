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
        foreach ($this->parameters as $test) {
            if (empty($test)) {
                $this->errors[] = 'parameters are not fuond!';
                continue;
            }

            if (empty($test['type']) || !array_key_exists(strtolower($test['type']), $this->type)) {
                $this->errors[] = 'type =' . $test['type'] ?? '""' . ' - unknown';
                continue;
            }
            $obj = "Mylibs\\Test" . $this->type[$test['type']];

            $objectTest = new $obj();

            $err = $testService->run($objectTest, $test);

            if (!empty($err)) {
                $this->errors[] = $this->type[$test['type']] . " - " . $err;
            }
        }
        return empty($this->errors) ? true : $this->errors;
    }

    public function page()
    {
        $testService = new TestService;

        $this->status = [];

        foreach ($this->parameters as $test) {
            $obj = "Mylibs\\Test" . $this->type[$test['type']];

            $objectTest = new $obj();

            $err = $testService->run($objectTest, $test, true);

            $this->status[] = $this->type[$test['type']] . " - " . $err;
        }
        $data = $this->status;
        require_once(dirname(__FILE__) . "/page.php");
    }
}
