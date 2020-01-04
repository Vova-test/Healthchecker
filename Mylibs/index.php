<?php
    $dsn = "mysql:host=localhost;port=3306;dbname=".DB_NAME.";charset=utf8";
    //var_dump($dsn);//die();
    $pdo = new PDO($dsn, DB_USER, DB_PASS);

    define("PG_USER", "postgresql1");
    define("PG_PASS", "1111");
    $pgdsn = "pgsql:host=localhost;port=5432;dbname=lessons;user=postgresql1;password=1111";
    //$pgdsn = "pgsql:host=localhost;port=5432;dbname=lessons"
    //$dbh = new PDO($pgdsn,PG_USER,PG_PASS);
    $dbh = new PDO($pgdsn);
    //Memcached
    /* <?php if (class_exists('Memcache')) { $server = 'localhost'; if (!empty($_REQUEST['server'])) { $server = $_REQUEST['server']; } $memcache = new Memcache; $isMemcacheAvailable = @$memcache->connect($server); if ($isMemcacheAvailable) { $aData = $memcache->get('data'); echo '<pre>'; if ($aData) { echo '<h2>Data from Cache:</h2>'; print_r($aData); } else { $aData = array( 'me' => 'you', 'us' => 'them', ); echo '<h2>Fresh Data:</h2>'; print_r($aData); $memcache->set('data', $aData, 0, 300); } $aData = $memcache->get('data'); if ($aData) { echo '<h3>Memcache seem to be working fine!</h3>'; } else { echo '<h3>Memcache DOES NOT seem to be working!</h3>'; } echo '</pre>'; } } if (!$isMemcacheAvailable) { echo 'Memcache not available'; } ?>*/
    //<?php public function test() { // memcache test - make sure you have memcache extension installed and the deamon is up and running $memcache = new Memcache; $memcache->connect('localhost', 11211) or die ("Could not connect"); $version = $memcache->getVersion(); echo "Server's version: ".$version."<br/>\n"; $tmp_object = new stdClass; $tmp_object->str_attr = 'test'; $tmp_object->int_attr = 123; $memcache->set('key', $tmp_object, false, 10) or die ("Failed to save data at the server"); echo "Store data in the cache (data will expire in 10 seconds)<br/>\n"; $get_result = $memcache->get('key'); echo "Data from the cache:<br/>\n"; var_dump($get_result); } 

    error_reporting(E_ALL & ~E_NOTICE);

    $mc = new Memcached();
    $mc->addServer("localhost", 11211);

    $mc->set("foo", "Hello!");
    $mc->set("bar", "Memcached...");

    $arr = array(
        $mc->get("foo"),
        $mc->get("bar")
    );
    var_dump($arr);
    $redis = new Redis();
    $redis->connect('localhost',6379);
    if ($redis->ping()) {
        var_dump("PONG\n");
    }
    die();
?>