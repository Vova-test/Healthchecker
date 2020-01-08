<?php

namespace Mylibs;

use \PDO;
use \PDOException;
use Mylibs\TestHealthInterface;

class TestMySQL implements TestHealthInterface
{
    protected $error;
    protected $status;
    protected $query;
    protected $timeout;

    public function testing($data = [], $page = false)
    {
        $this->error = '';
        $this->query = $data['query'] ?? "show tables";
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
        if (empty($config['database'])) {
            $this->error = "name database is empty";
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

        $dsn = "mysql:host=" . ($config['host'] ?? "localhost")
            . ";port=" . ($config['port'] ?? '3306')
            . ";dbname=" . $config['database'];

        $this->connectDb($dsn, $config['user'], $config['password']);
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
                $this->error = 'error by timeout, timeout = $timeMySQL"';
            } else {
                $this->status = "MySQL is working, timeout = " . $timeDb;
            }
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
        }
    }
}
