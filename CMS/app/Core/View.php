<?php

declare(strict_types=1);

namespace App\Core;

final class View
{
    public static function render(string $template, array $data = [], string $layout = 'main'): void
    {
        $viewsDir = __DIR__ . '/../../views/';
        $templateFile = $viewsDir . $template . '.php';
        $layoutFile = $viewsDir . 'layouts/' . $layout . '.php';

        if (!is_file($templateFile) || !is_file($layoutFile)) {
            http_response_code(500);
            echo 'View file missing.';
            return;
        }

        extract($data, EXTR_SKIP);
        ob_start();
        include $templateFile;
        $content = ob_get_clean();
        $flashes = Flash::all();
        include $layoutFile;
    }
}
