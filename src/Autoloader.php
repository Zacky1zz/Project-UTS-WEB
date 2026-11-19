<?php
declare(strict_types=1);

namespace App;

/** Autoloader PSR-4 sederhana (namespace App\ => folder src/). Tidak butuh Composer. */
final class Autoloader
{
    public static function register(string $baseDir): void
    {
        spl_autoload_register(static function (string $class) use ($baseDir): void {
            $prefix = 'App\\';
            if (!str_starts_with($class, $prefix)) {
                return;
            }
            $file = $baseDir . '/' . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';
            if (is_file($file)) {
                require $file;
            }
        });
    }
}
