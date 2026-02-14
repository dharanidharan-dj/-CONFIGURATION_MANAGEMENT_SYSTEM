<?php

declare(strict_types=1);

$basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');

return [
    'app_name' => 'Configuration Management System',
    'base_path' => $basePath === '/' ? '' : $basePath,
    'db' => [
        'host' => '127.0.0.1',
        'port' => '3306',
        'name' => 'cms_db',
        'user' => 'root',
        'pass' => '',
        'charset' => 'utf8mb4',
    ],
    'upload' => [
        'dir' => __DIR__ . '/../uploads/posts',
        'url' => '/uploads/posts',
        'max_bytes' => 2 * 1024 * 1024,
        'allowed_mime' => [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif',
        ],
    ],
];
