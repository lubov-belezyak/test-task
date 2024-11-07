<?php

namespace Src\Migrations;

use Src\Services\Database;
class CreateUsersTableMigration
{
    protected $pdo;

    public function __construct()
    {
        $database = new Database();
        $this->pdo = $database->getConnection();
    }

    public function up()
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                first_name VARCHAR(255) NOT NULL,
                last_name VARCHAR(255) NOT NULL,
                middle_name VARCHAR(255) DEFAULT NULL,
                email VARCHAR(255) UNIQUE NOT NULL,
                password VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            );
        ";
        $this->pdo->exec($sql);
    }

    public function down()
    {
        $sql = "DROP TABLE IF EXISTS users;";
        $this->pdo->exec($sql);
    }
}