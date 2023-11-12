<?php

declare(strict_types=1);

define('APP_BASE_PATH', dirname(__DIR__));
require APP_BASE_PATH . '/src/functions.php';

run_webapp_rr("roadrunner");
