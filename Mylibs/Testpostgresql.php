<?php

namespace Mylibs;

use \PDO;
use \PDOException;
use Mylibs\TestHealthInterface;

class TestPostgreSQL implements TestHealthInterface
{
    protected $error = [];
    protected $query;
    protected $timeout;

    public function testing($data = [])
    {
        $this->error = [];
        $this->timeout = $data['timeout'] ?? 0;
        $this->query = $data['query'] ?? "SELECT 
                                              * 
                                          FROM
                                              pg_catalog.pg_tables 
                                          WHERE
                                              schemaname != 'pg_catalog' 
                                              AND schemaname != 'information_schema'";

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

        $dsn = "pgsql:host=" . ($config['host'] ?? "localhost") . ";port=" . ($config['port'] ?? '3306') . ";dbname=" . $config['database'] . ";user=" . $config['user'] . ";password=" . $config['password'];

        $this->connectDb($dsn);
    }

    protected function connectDb($dsn)
    {
        try {
            $pdo = new PDO($dsn);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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
                $this->status = "PostgreSQL is Ok. Timeout = $timeMySQL";
            }
        } catch (PDOException $e) {
            $this->error[] = $e->getMessage();
        }        
    }
}
