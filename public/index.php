<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../routes/api.php';

use Src\Services\Database;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();


$database = new Database();
$connection = $database->getConnection();