<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Framework\Factories;

use DI\Bridge\Slim\Bridge;
use DI\Container;
use DI\ContainerBuilder;
use DI\Definition\Source\DefinitionSource;
use Exception;
use Psr\Container\ContainerInterface;
use Slim\App;
use ToniLiesche\Roadrunner\Core\Application\Framework\Interfaces\ContainerConfiguratorInterface;
use ToniLiesche\Roadrunner\Core\Application\Framework\Interfaces\MiddlewareConfiguratorInterface;
use ToniLiesche\Roadrunner\Core\Application\Framework\Interfaces\RouteConfiguratorInterface;
use ToniLiesche\Roadrunner\Core\Application\Framework\Models\Config;
use ToniLiesche\Roadrunner\Infrastructure\FileSystem\Exceptions\FileSystemException;
use ToniLiesche\Roadrunner\Infrastructure\FileSystem\Models\Directory;
use ToniLiesche\Roadrunner\Infrastructure\FileSystem\Service\FileSystemService;

final readonly class AppFactory
{
    public function __construct(
        private ContainerInterface $container,
        private RouteConfiguratorInterface $routeConfigurator,
        private MiddlewareConfiguratorInterface $middlewareConfigurator,
        private FileSystemService $fileSystemService = new FileSystemService()
    ) {
    }

    /**
     * @throws FileSystemException
     */
    public function create(Config $config): App
    {
        $app = Bridge::create($this->container);
        $this->configureRouter($config, $app);
        $this->middlewareConfigurator->configureMiddlewares($this->container, $app);

        return $app;
    }

    /**
     * @throws FileSystemException
     */
    private function configureRouter(Config $config, App $app): void
    {
        $routerConfig = $config->getFrameworkConfig()->getRouterConfig();

        if ($routerConfig->isCachingEnabled()) {
            $cacheDirectory = new Directory(['basePath' => $routerConfig->getCachePath(), 'path' => 'metadata']);
            if (false === $this->fileSystemService->checkDirectoryExists(
                    $cacheDirectory
                ) && false === $this->fileSystemService->createDirectory($cacheDirectory, 0755, true)) {
                throw new FileSystemException('Could not create router cache directory');
            }

            $cacheFile = \sprintf('%s/routes.cache.php', $routerConfig->getCachePath());
            $routeCollector = $app->getRouteCollector();
            $routeCollector->setCacheFile($cacheFile);
        }

        $this->routeConfigurator->configureRoutes($app);
    }
}
