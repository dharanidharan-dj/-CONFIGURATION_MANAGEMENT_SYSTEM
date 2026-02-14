<?php

declare(strict_types=1);

namespace App\Core;

final class Response
{
    public static function redirect(string $path): void
    {
        $url = url($path);
        header('Location: ' . $url);
        exit;
    }
}
