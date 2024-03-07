<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Infrastructure\Metrics\Services;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Spiral\Goridge\RPC\RPC;
use Spiral\RoadRunner\Metrics\MetricsFactory;
use Spiral\RoadRunner\Metrics\MetricsOptions;
use ToniLiesche\Roadrunner\Infrastructure\Log\Logging;

class MetricsServiceFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): MetricsService
    {
        $rpc = $container->get(RPC::class);
        $factory = new MetricsFactory(Logging::psr());
        $options = new MetricsOptions(
            retryAttempts: 3,
            retrySleepMicroseconds: 50,
            suppressExceptions: true
        );

        $client = $factory->create($rpc, $options);

        return new MetricsService($client);
    }
}
