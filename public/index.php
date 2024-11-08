<?php

require_once __DIR__ . '/../vendor/autoload.php';


use Src\Services\Database;
use Src\Services\ExceptionHandler;

// Регистрируем обработчики
set_exception_handler([ExceptionHandler::class, 'handleException']);
set_error_handler([ExceptionHandler::class, 'handleError']);
register_shutdown_function([ExceptionHandler::class, 'handleShutdown']);

$db = new Database();
require_once __DIR__ . '/../routes/api.php';
