<?php

namespace Src\Services;

use mysqli;
use PDO;
use PDOException;

class Database
{
    protected $pdo;

    public function __construct() {
        $config = require_once __DIR__ . '/../../config/database.php';
        $this->pdo = new \PDO(
            "mysql:host={$config['host']};dbname={$config['database']};charset={$config['charset']}",
            $config['username'],
            $config['password'],
            [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
        );
    }

    public function getConnection() {
        return $this->pdo;
    }
}