<?php
namespace Mylibs;

    class HealthChecker{

        protected $parameters = [];
        protected $errors = [];
        protected $status = [];
        protected $type = ['mysql'=>'MySQL', 'pgsql'=>'PostgreSQL', 'memcached'=>'Memcached', 'redis'=>'Redis', 'free_space'=>'FreeSpace', 'folder_is_writable'=>'WritableFolder'];

        public function add($parameters = [])
        { 
            $this->parameters[] = $parameters;
        }

        public function test()
        {
            foreach ($this->parameters as $parameter) {

                if (empty($parameter)){
                    $this->errors[] = 'Parameters are not fuond!';
                    continue;
                }

                if (empty($parameter['type']) || !array_key_exists(strtolower($parameter['type']),$this->type)){
                    $this->errors[] = 'Type ='.($parameter['type']) ?? ''.' unknown';
                    continue;
                }
                $this->{"test".$this->type[$parameter['type']]}($parameter);
            }
            return (empty($this->errors) ? TRUE : $this->errors);
        } 

        public function page()
        {
            $this->status = [];
            foreach ($this->parameters as $parameter) {

                $this->{"test".$this->type[$parameter['type']]}($parameter);
            }
            $data = $this->status;
            require_once(ROOT_PATH."/Mylibs/page.php");
        }

        protected function testMySQL($parameters)
        {
            try {
                $dsn = $parameters['type'].":host=".($parameters['credentials']['host'] ?? "localhost").";port=".($parameters['credentials']['port'] ?? '3306').";dbname=".$parameters['credentials']['datebase'];

                $pdo = new \PDO($dsn, $parameters['credentials']['user'], $parameters['credentials']['password'], array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES UTF8", 
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ, \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION));

                $timeMySQL = time();
                    $stmt = $pdo->query($parameters['query'] ?? "show tables");
                $timeMySQL = time() - $timeMySQL;
                $status = 'MySQL dbName="'.$parameters['credentials']['datebase'].'" status=';

                if (!empty($parameters['timeout']) && $parameters['timeout'] < $timeMySQL) {
                    $this->status[] = $status.'FALSE';
                } else {
                    $this->status[] = $status.'TRUE';
                }

            } catch (\PDOException $e) {

                $this->errors[] =  $e->getMessage();
            }
        }

        protected function testPostgreSQL($parameters)
        {
            try {

                $pgdsn = $parameters['type'].":host=".($parameters['credentials']['host'] ?? "localhost").";port=".($parameters['credentials']['port'] ?? '5432').";dbname=".$parameters['credentials']['datebase'].";user=".$parameters['credentials']['user'].";password=".$parameters['credentials']['password'];

                $dbh = new \PDO($pgdsn);
                $dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

                $timeMySQL = time();
                    $stmt = $dbh->query($parameters['query'] ?? "SELECT * FROM pg_catalog.pg_tables WHERE schemaname != 'pg_catalog' AND schemaname != 'information_schema'");
                $timeMySQL = time() - $timeMySQL;
                $status = 'PostgreSQL dbName="'.$parameters['credentials']['datebase'].'" status=';

                if (!empty($parameters['timeout']) && $parameters['timeout'] < $timeMySQL) {
                    $this->status[] = $status.'FALSE';
                } else {
                    $this->status[] = $status.'TRUE';
                }

            } catch (\PDOException $e) {
                
                $this->errors[] = ($e->getMessage());
            }
        }

        protected function testMemcached($parameters)
        {
            try {

                $status = 'Memcached status=';
                $mc = new \Memcached();
                $mc->addServer($parameters['credentials']['host'] ?? "localhost", $parameters['credentials']['port'] ?? 11211);
                $mc->set("foo", "TRUE");
                $this->status[] = empty($status.($mc->get("foo"))) ? "TRUE" :"FALSE";
                
            } catch (Exception $e) {

                $this->errors[] = ($e->getMessage());                
            }            
        }

        protected function testRedis($parameters)
        {
            try {

                $status = 'Redis status=';
                $redis = new \Redis();
                $redis->connect($parameters['credentials']['host'] ?? "localhost", $parameters['credentials']['port'] ?? 6379);

                $this->status[] = $status.($redis->ping() ? "TRUE":"FALSE");
                
            } catch (Exception $e) {
                $this->errors[] = ($e->getMessage());                
            }            
        }

        protected function testFreeSpace()
        {
            try {

                $this->status[] = "Free space = ".disk_free_space("/");
                
            } catch (Exception $e) {

                $this->errors[] = ($e->getMessage());                
            }            
        }

        protected function testWritableFolder($parameters)
        {
            try {

                $this->status[] = 'Folder "'.$parameters['path'].'" is writable = '.(is_writable($parameters['path']) ? "TRUE":"FALSE");
                
            } catch (Exception $e) {

                $this->errors[] = ($e->getMessage());                
            }
        }
    }   
