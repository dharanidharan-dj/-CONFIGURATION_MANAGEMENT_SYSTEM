<?php

declare(strict_types=1);

session_start();

$config = require __DIR__ . '/../config/app.php';

require __DIR__ . '/helpers.php';
require __DIR__ . '/Core/Autoloader.php';

App\Core\Autoloader::register(__DIR__);
App\Core\Database::connect($config['db']);

App\Core\App::setConfig($config);
