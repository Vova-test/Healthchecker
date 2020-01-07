<?php

define("ROOT_PATH", dirname(__FILE__, 2));
define("SITE_URL", "http://myproject.loc");
require_once(ROOT_PATH . "/vendor/autoload.php");

/*MyLibs*/

use Mylibs\HealthChecker;

$checker = new HealthChecker();

$checker->add(
    [
        'type' => 'mysql',
        'credentials' => [
            'host' => 'localhost',
            'port' => 3306,
            'user' => 'root',
            'password' => 'root',
            'database' => 'lesson'
        ],
        'query' => 'SELECT
                        * 
                    FROM
                        users',
        'timeout' => '10'
    ]
);

$checker->add(
    [
        'type' => 'pgsql',
        'credentials' => [
            'host' => 'localhost',
            'port' => 5432,
            'user' => 'postgresql1',
            'password' => '1111',
            'database' => 'lessons'
        ],
        'query' => 'SELECT
                        * 
                    FROM
                        users',
        'timeout' => '10'
    ]
);

$checker->add(
    [
        'type' => 'memcached',
        'credentials' => [
            'host' => 'localhost',
            'port' => 11211
        ]
    ]
);

$checker->add(
    [
        'type' => 'redis',
        'credentials' => [
            'host' => 'localhost',
            'port' => 6379
        ]
    ]
);

$checker->add(
    [
        'type' => 'free_space',
        'threshold' => 12345
    ]
);

$checker->add(
    [
        'type' => 'folder_is_writable',
        'path' => dirname(dirname(__FILE__)) . '/storage'
    ]
);
var_dump($checker->test());
//$checker->page();

?>