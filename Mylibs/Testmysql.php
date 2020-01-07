<?php

namespace Mylibs;

use \PDO;
use \PDOException;
use Mylibs\TestHealthInterface;

class TestMySQL implements TestHealthInterface
{
    protected $error = [];
    protected $query;
    protected $timeout;

    public function testing($data = [])
    {
        $this->error = [];
        $this->query = $data['query'] ?? "show tables";
        $this->timeout = $data['timeout'] ?? 0;

        $this->validDsnConfig($data['credentials']);
        return $this->error;
    }

    protected function validDsnConfig($config = [])
    {
        if (empty($config['database'])) {
            $this->error = 'Testing MySQL - "Name Database is empty"';
            return;
        }

        if (empty($config['user'])) {
            $this->error = 'Testing MySQL - "User Database is empty"';
            return;
        }

        if (empty($config['password'])) {
            $this->error = 'Testing MySQL - "Password Database is empty"';
            return;
        }

        $dsn = "mysql:host=" . ($config['host'] ?? "localhost") . ";port=" . ($config['port'] ?? '3306') . ";dbname=" . $config['database'];
        
        $this->connectDb($dsn,  $config['user'], $config['password']);
    }

    protected function connectDb($dsn, $user, $password)
    {
        try {
            $pdo = new PDO(
                $dsn, $user, $password, [
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES UTF8",
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                    ]
            );
            $this->timing($pdo);

        } catch (PDOException $e) {
            $this->error[] = $e->getMessage(); 
        }           
    }

    protected function timing($pdo)
    {
        try {
            $timeMySQL = time();
                $stmt = $pdo->query($this->query);
            $timeMySQL = time() - $timeMySQL;

            if (!empty($this->timeout) && $this->timeout < $timeMySQL) {
                $this->errors[] = 'Error by timeout';
            } else {
                $this->status = "MySQL is Ok. Timeout = $timeMySQL";
            }
        } catch (PDOException $e) {
            $this->error[] = $e->getMessage();
        }        
    }
}
