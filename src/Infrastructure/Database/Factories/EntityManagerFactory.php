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
use ToniLiesche\Roadrunner\Core\Application\Config\Exceptions\MissingConfigValueException;
use ToniLiesche\Roadrunner\Core\Application\Config\Models\Config;
use ToniLiesche\Roadrunner\Core\Application\Config\Models\DatabaseConfig;
use ToniLiesche\Roadrunner\Core\Application\Library\Enums\PHPRuntime;
use ToniLiesche\Roadrunner\Infrastructure\Database\Service\EntityManagerWrapper;
use ToniLiesche\Roadrunner\Infrastructure\Engine\Services\RoadrunnerRequestCleaningService;
use ToniLiesche\Roadrunner\Infrastructure\FileSystem\Exceptions\FileSystemException;
use ToniLiesche\Roadrunner\Infrastructure\FileSystem\Models\Directory;
use ToniLiesche\Roadrunner\Infrastructure\FileSystem\Service\FileSystemService;
use ToniLiesche\Roadrunner\Infrastructure\Log\Logging;

readonly final class EntityManagerFactory
{
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
        $fileSystemService = $container->get(FileSystemService::class);

        $config = ORMSetup::createAttributeMetadataConfiguration(
            $databaseConfig->getEntityPaths(),
            $databaseConfig->isDebugEnabled(),
            $databaseConfig->getProxyPath(),
        );

        if (true === $databaseConfig->isDebugEnabled() && null !== ($logger = Logging::sql())) {
            $sqlLogMiddleware = new Middleware($logger);
            $config->setMiddlewares([$sqlLogMiddleware]);
        }

        if (false === $databaseConfig->isCachingEnabled()) {
            $config->setAutoGenerateProxyClasses(AbstractProxyFactory::AUTOGENERATE_EVAL);

            return $config;
        }

        $config->setAutoGenerateProxyClasses(AbstractProxyFactory::AUTOGENERATE_FILE_NOT_EXISTS_OR_CHANGED);
        $metadataCacheDirectory = new Directory(['basePath' => $databaseConfig->getCachePath(), 'path' => 'metadata']);
        if (false === $fileSystemService->checkDirectoryExists(
                $metadataCacheDirectory
            ) && false === $fileSystemService->createDirectory($metadataCacheDirectory, 0755, true)) {
            throw new FileSystemException('Could not create database meta data cache directory');
        }

        $hydrationCacheDirectory = new Directory(['basePath' => $databaseConfig->getCachePath(), 'path' => 'hydration']
        );
        if (false === $fileSystemService->checkDirectoryExists(
                $hydrationCacheDirectory
            ) && false === $fileSystemService->createDirectory($hydrationCacheDirectory, 0755, true)) {
            throw new FileSystemException('Could not create database hydration cache directory');
        }

        $queryCacheDirectory = new Directory(['basePath' => $databaseConfig->getCachePath(), 'path' => 'query']);
        if (false === $fileSystemService->checkDirectoryExists(
                $queryCacheDirectory
            ) && false === $fileSystemService->createDirectory($queryCacheDirectory, 0755, true)) {
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
