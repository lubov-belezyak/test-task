<?php

namespace Src\Services;

class ExceptionHandler
{
    public static function handleException($exception)
    {
        // Логирование исключения
        error_log("Exception caught: " . $exception->getMessage());

        // Определение кода ответа
        $statusCode = 500;
        if ($exception instanceof \PDOException) {
            $statusCode = 503; // Например, ошибка подключения к базе данных
        }

        // Устанавливаем статус ответа, если он еще не установлен
        if (http_response_code() === 200) {
            http_response_code($statusCode);
        }

        // Формирование данных для вывода
        $debugMode = getenv('APP_DEBUG') === 'true';
        $errorDetails = $debugMode ? [
            'message' => $exception->getMessage(),
            'trace' => $exception->getTrace(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
        ] : 'An unexpected error occurred';

        // JSON-ответ с ошибкой
        echo json_encode([
            'error' => [
                'message' => 'Internal Server Error',
                'details' => $errorDetails,
            ]
        ]);
    }

    public static function handleError($errno, $errstr, $errfile, $errline)
    {
        error_log("Error [$errno]: $errstr in $errfile on line $errline");

        // Определение критичности ошибки
        if ($errno === E_ERROR || $errno === E_CORE_ERROR || $errno === E_COMPILE_ERROR) {
            if (http_response_code() === 200) {
                http_response_code(500);
            }
        }

        // Формирование данных для вывода
        $debugMode = getenv('APP_DEBUG') === 'true';
        $errorDetails = $debugMode ? [
            'message' => $errstr,
            'file' => $errfile,
            'line' => $errline,
        ] : 'An unexpected error occurred';

        // JSON-ответ с ошибкой
        echo json_encode([
            'error' => [
                'message' => 'Internal Server Error',
                'details' => $errorDetails,
            ]
        ]);

        return true;
    }

    public static function handleShutdown()
    {
        $error = error_get_last();
        if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
            self::handleError($error['type'], $error['message'], $error['file'], $error['line']);
        }
    }
}
