<?php

namespace Mylibs;

use \PDO;
use \PDOException;
use Mylibs\TestHealthInterface;

class TestPostgreSQL implements TestHealthInterface
{
    protected $error;
    protected $status;
    protected $query;
    protected $timeout;

    public function testing($data = [], $page = false)
    {
        $this->error = '';
        $this->timeout = $data['timeout'] ?? 0;
        $this->query = $data['query'] ?? "SELECT 
                                              * 
                                          FROM
                                              pg_catalog.pg_tables 
                                          WHERE
                                              schemaname != 'pg_catalog' 
                                              AND schemaname != 'information_schema'";

        $this->validDsnConfig($data['credentials']);

        if ($page) {
            return $this->status ?? $this->error;
        } else {
            return $this->error;
        }
    }

    protected function validDsnConfig($config = [])
    {
        if (empty($config['database'])) {
            $this->error = "name natabase is empty";
            return;
        }

        if (empty($config['user'])) {
            $this->error = "user database is empty";
            return;
        }

        if (empty($config['password'])) {
            $this->error = "password database is empty";
            return;
        }

        $dsn = "pgsql:host=" . ($config['host'] ?? "localhost")
            . ";port=" . ($config['port'] ?? '3306')
            . ";dbname=" . $config['database']
            . ";user=" . $config['user']
            . ";password=" . $config['password'];

        $this->connectDb($dsn);
    }

    protected function connectDb($dsn)
    {
        try {
            $pdo = new PDO($dsn);

            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $this->timing($pdo);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
        }
    }

    protected function timing($pdo)
    {
        try {
            $timeDb = time();

            $stmt = $pdo->query($this->query);

            $timeDb = time() - $timeDb;

            if (!empty($this->timeout) && $this->timeout < $timeDb) {
                $this->error = 'error by timeout';
            } else {
                $this->status = "it is Ok. timeout = " . $timeDb;
            }
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
        }
    }
}
