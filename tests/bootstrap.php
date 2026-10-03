<?php

require __DIR__ . '/../vendor/autoload.php';

spl_autoload_register(function ($class) {
    if (str_starts_with($class, 'App\\')) {
        $path = dirname(__DIR__) . '/app/' . str_replace('\\', '/', substr($class, 4)) . '.php';
        if (file_exists($path)) {
            require_once $path;
            return true;
        }
    }
    if (str_starts_with($class, 'Database\\')) {
        $path = dirname(__DIR__) . '/database/' . str_replace('\\', '/', substr($class, 9)) . '.php';
        if (file_exists($path)) {
            require_once $path;
            return true;
        }
    }
}, true, true);
