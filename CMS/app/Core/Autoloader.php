<?php

declare(strict_types=1);

namespace App\Core;

final class Autoloader
{
    private static string $baseDir;

    public static function register(string $baseDir): void
    {
        self::$baseDir = rtrim($baseDir, '/\\') . DIRECTORY_SEPARATOR;
        spl_autoload_register([self::class, 'autoload']);
    }

    private static function autoload(string $class): void
    {
        $prefix = 'App\\';
        if (!str_starts_with($class, $prefix)) {
            return;
        }

        $relative = str_replace('\\', DIRECTORY_SEPARATOR, substr($class, strlen($prefix)));
        $file = self::$baseDir . $relative . '.php';

        if (is_file($file)) {
            require $file;
        }
    }
}
