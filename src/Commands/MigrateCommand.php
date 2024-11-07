<?php

namespace Src\Commands;

use Src\Services\Database;

class MigrateCommand extends Command
{
    public function execute(array $args)
    {
        $migrationFiles = glob(__DIR__ . "/../../database/migrations/*.php");

        if (empty($migrationFiles)) {
            echo "Миграции не найдены.\n";
            return;
        }

        foreach ($migrationFiles as $migrationFile) {
            $className = $this->getClassNameFromFile($migrationFile);
            $this->requireMigrationFile($migrationFile);

            // Создаем экземпляр класса миграции и выполняем метод up
            $migration = new $className();
            $migration->up();
        }

        echo "Все миграции выполнены успешно.\n";
    }

    private function getClassNameFromFile($file)
    {
        // Извлекаем имя класса из файла миграции
        $filename = basename($file, '.php');
        $className = str_replace(['-', '_'], '', ucwords($filename, '-_'));

        return "Src\\Migrations\\{$className}";
    }

    private function requireMigrationFile($file)
    {
        // Подключаем файл миграции
        require_once $file;
    }
}
