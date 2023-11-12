<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Framework\Factories;

use DI\ContainerBuilder;
use Exception;
use Psr\Container\ContainerInterface;
use ToniLiesche\Roadrunner\Core\Application\Framework\Exceptions\ContainerBuildFailedException;
use ToniLiesche\Roadrunner\Core\Application\Framework\Interfaces\ContainerConfiguratorInterface;
use ToniLiesche\Roadrunner\Core\Application\Framework\Models\Config;

class ContainerFactory
{
    /**
     * @throws ContainerBuildFailedException
     */
    public function createContainer(Config $config, ContainerConfiguratorInterface $configurator): ContainerInterface
    {
        $builder = new ContainerBuilder();

        $diConfig = $config->getFrameworkConfig()->getDiConfig();
        if ($diConfig->isCachingEnabled()) {
            $builder->enableDefinitionCache($config->getApplicationConfig()->getName());
        }

        if ($diConfig->isCompilationEnabled()) {
            $builder->enableCompilation($diConfig->getCachePath());
        }

        if ($diConfig->isProxyGenerationEnabled()) {
            $builder->writeProxiesToFile(true, $diConfig->getCachePath());
        }

        $builder->addDefinitions($configurator->getDefinitions());

        try {
            $container = $builder->build();
        } catch (Exception $ex) {
            throw new ContainerBuildFailedException('Caught exception while building dependency injection container', $ex->getCode(), $ex);
        }
        $container->set(Config::class, $config);

        return $container;
    }
}
