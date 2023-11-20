<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Config\Interfaces;

use Psr\Container\ContainerInterface;
use Slim\App;

interface MiddlewareConfiguratorInterface
{
    public function configureMiddlewares(ContainerInterface $container, App $app): void;
}
