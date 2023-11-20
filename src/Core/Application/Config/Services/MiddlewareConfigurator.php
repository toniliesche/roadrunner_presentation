<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Config\Services;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Slim\App;
use ToniLiesche\Roadrunner\Core\Application\Config\Interfaces\MiddlewareConfiguratorInterface;
use ToniLiesche\Roadrunner\Core\Application\Config\Models\Config;
use ToniLiesche\Roadrunner\Core\Application\Framework\Middlewares\RequestIdMiddleware;
use ToniLiesche\Roadrunner\Core\Application\Framework\Services\ErrorHandler;
use ToniLiesche\Roadrunner\Core\Application\Library\Enums\PHPRuntime;

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
        $errorMiddleware = $app->addErrorMiddleware(true, true, true);
        $errorMiddleware->setDefaultErrorHandler($container->get(ErrorHandler::class));
    }
}
