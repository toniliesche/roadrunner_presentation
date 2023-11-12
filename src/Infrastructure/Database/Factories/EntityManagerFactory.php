<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Infrastructure\Database\Factories;

use Doctrine\Common\EventManager;
use Doctrine\Common\Proxy\AbstractProxyFactory;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Logging\Middleware;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\MissingMappingDriverImplementation;
use Doctrine\ORM\ORMSetup;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use ToniLiesche\Roadrunner\Core\Application\Framework\Enums\PHPRuntime;
use ToniLiesche\Roadrunner\Core\Application\Framework\Exceptions\MissingConfigValueException;
use ToniLiesche\Roadrunner\Core\Application\Framework\Models\Config;
use ToniLiesche\Roadrunner\Core\Application\Framework\Models\DatabaseConfig;
use ToniLiesche\Roadrunner\Infrastructure\Database\Service\EntityManagerWrapper;
use ToniLiesche\Roadrunner\Infrastructure\Engine\Services\RoadrunnerRequestCleaningService;
use ToniLiesche\Roadrunner\Infrastructure\FileSystem\Exceptions\FileSystemException;
use ToniLiesche\Roadrunner\Infrastructure\FileSystem\Models\Directory;
use ToniLiesche\Roadrunner\Infrastructure\FileSystem\Service\FileSystemService;
use ToniLiesche\Roadrunner\Infrastructure\Log\Interfaces\SqlLoggerInterface;

readonly final class EntityManagerFactory
{
    public function __construct(private FileSystemService $fileSystemService)
    {
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws MissingConfigValueException
     * @throws NotFoundExceptionInterface
     * @throws Exception
     * @throws FileSystemException
     * @throws MissingMappingDriverImplementation
     */
    public function __invoke(ContainerInterface $container): EntityManagerInterface
    {
        $config = $container->get(Config::class);

        if (!$config->hasDatabaseConfig()) {
            throw new MissingConfigValueException('Could not retrieve database config');
        }

        $databaseConfig = $config->getDatabaseConfig();
        $configuration = $this->buildConfig($databaseConfig, $container);
        $connection = DriverManager::getConnection(
            $this->buildParams($databaseConfig),
            $configuration,
            new EventManager()
        );

        $entityManager = new EntityManager($connection, $configuration);

        if (PHPRuntime::ROADRUNNER === $config->getSystemConfig()->getRuntime()) {
            $roadrunnerRequestCleaningService = $container->get(RoadrunnerRequestCleaningService::class);

            $entityManagerWrapper = new EntityManagerWrapper($entityManager);
            $roadrunnerRequestCleaningService->registerBeforeRequest($entityManagerWrapper);
        }

        return $entityManager;
    }

    private function buildParams(DatabaseConfig $databaseConfig): array
    {
        return [
            'driver' => 'mysqli',
            'host' => $databaseConfig->getHost(),
            'port' => $databaseConfig->getPort(),
            'user' => $databaseConfig->getUsername(),
            'password' => $databaseConfig->getPassword(),
            'dbname' => $databaseConfig->getDatabase(),
            'charset' => 'utf8',
            'persistent' => true,
        ];
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws FileSystemException
     * @throws NotFoundExceptionInterface
     */
    private function buildConfig(DatabaseConfig $databaseConfig, ContainerInterface $container): Configuration
    {
        $config = ORMSetup::createAttributeMetadataConfiguration(
            $databaseConfig->getEntityPaths(),
            $databaseConfig->isDebugEnabled(),
            $databaseConfig->getProxyPath(),
        );

        if (true === $databaseConfig->isDebugEnabled()) {
            $sqlLogMiddleware = new Middleware($container->get(SqlLoggerInterface::class));
            $config->setMiddlewares([$sqlLogMiddleware]);
        }

        if (false === $databaseConfig->isCachingEnabled()) {
            $config->setAutoGenerateProxyClasses(AbstractProxyFactory::AUTOGENERATE_EVAL);

            return $config;
        }

        $config->setAutoGenerateProxyClasses(AbstractProxyFactory::AUTOGENERATE_FILE_NOT_EXISTS_OR_CHANGED);
        $metadataCacheDirectory = new Directory(['basePath' => $databaseConfig->getCachePath(), 'path' => 'metadata']);
        if (false === $this->fileSystemService->checkDirectoryExists(
                $metadataCacheDirectory
            ) && false === $this->fileSystemService->createDirectory($metadataCacheDirectory, 0755, true)) {
            throw new FileSystemException('Could not create database meta data cache directory');
        }

        $hydrationCacheDirectory = new Directory(['basePath' => $databaseConfig->getCachePath(), 'path' => 'hydration']
        );
        if (false === $this->fileSystemService->checkDirectoryExists(
                $hydrationCacheDirectory
            ) && false === $this->fileSystemService->createDirectory($hydrationCacheDirectory, 0755, true)) {
            throw new FileSystemException('Could not create database hydration cache directory');
        }

        $queryCacheDirectory = new Directory(['basePath' => $databaseConfig->getCachePath(), 'path' => 'query']);
        if (false === $this->fileSystemService->checkDirectoryExists(
                $queryCacheDirectory
            ) && false === $this->fileSystemService->createDirectory($queryCacheDirectory, 0755, true)) {
            throw new FileSystemException('Could not create database query cache directory');
        }

        $metaCache = new FilesystemAdapter(directory: $metadataCacheDirectory->getAbsolutePath());
        $hydrationCache = new FilesystemAdapter(directory: $hydrationCacheDirectory->getAbsolutePath());
        $queryCache = new FilesystemAdapter(directory: $queryCacheDirectory->getAbsolutePath());

        $config->setMetadataCache($metaCache);
        $config->setHydrationCache($hydrationCache);
        $config->setQueryCache($queryCache);

        return $config;
    }
}
