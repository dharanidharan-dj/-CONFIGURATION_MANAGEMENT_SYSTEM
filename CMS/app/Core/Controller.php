<?php

declare(strict_types=1);

namespace App\Core;

abstract class Controller
{
    protected function view(string $template, array $data = [], string $layout = 'main'): void
    {
        View::render($template, $data, $layout);
    }
}
