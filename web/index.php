<?php

declare(strict_types=1);

define('APP_BASE_PATH', dirname(__DIR__));
require APP_BASE_PATH . '/src/functions.php';

try {
    run_webapp_fpm("roadrunner");
} catch (Throwable $t) {
    error_500('Something went wrong');
}
