<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Infrastructure\Log\Factories;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use ToniLiesche\Roadrunner\Core\Application\Framework\Models\Config;
use ToniLiesche\Roadrunner\Core\Application\Framework\Services\RequestIdService;
use ToniLiesche\Roadrunner\Infrastructure\Log\Services\LogEntryContextProvider;

class LogEntryContextProviderFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): LogEntryContextProvider
    {
        $config = $container->get(Config::class);

        $pid = \getmypid();

        return new LogEntryContextProvider(
            $container->get(RequestIdService::class),
            $config->getApplicationConfig()->getName(),
            $config->getSystemConfig()->getStage(),
            $config->getSystemConfig()->getHost(),
            $pid !== false ? $pid : 0
        );
    }
}
