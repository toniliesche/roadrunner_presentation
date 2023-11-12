<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Framework\Configuration;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Slim\App;
use ToniLiesche\Roadrunner\Core\Application\Framework\Enums\PHPRuntime;
use ToniLiesche\Roadrunner\Core\Application\Framework\Interfaces\MiddlewareConfiguratorInterface;
use ToniLiesche\Roadrunner\Core\Application\Framework\Middlewares\RequestIdMiddleware;
use ToniLiesche\Roadrunner\Core\Application\Framework\Models\Config;

final readonly class MiddlewareConfigurator implements MiddlewareConfiguratorInterface
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function configureMiddlewares(ContainerInterface $container, App $app): void
    {
        $config = $container->get(Config::class);
        if (PHPRuntime::PHP_FPM === $config->getSystemConfig()->getRuntime()) {
            $app->addMiddleware($container->get(RequestIdMiddleware::class));
        }

        /** These must be added last because they need to be executed first */
        $app->addRoutingMiddleware();
        $app->addErrorMiddleware(true, true, true);
    }
}