<?php

declare(strict_types=1);

use App\Core\App;
use App\Core\Csrf;

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function url(string $path = '/'): string
{
    $base = App::config('base_path', '');
    $path = '/' . ltrim($path, '/');
    return rtrim($base, '/') . $path;
}

function asset(string $path): string
{
    return url('/' . ltrim($path, '/'));
}

function csrf_field(): string
{
    return '<input type="hidden" name="_csrf" value="' . e(Csrf::token()) . '">';
}
