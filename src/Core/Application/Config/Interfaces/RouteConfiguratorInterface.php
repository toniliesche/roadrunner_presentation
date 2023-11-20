<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Config\Interfaces;

use Slim\App;

interface RouteConfiguratorInterface
{
    public function configureRoutes(App $app): void;
}
