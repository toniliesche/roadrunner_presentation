<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Infrastructure\Tracing\Factories;

use OpenTelemetry\API\Globals;
use OpenTelemetry\API\Trace\TracerInterface;
use OpenTelemetry\SDK\Trace\TracerProviderFactory;
use Psr\Container\ContainerInterface;

class TracerFactory
{
    public function __invoke(ContainerInterface $container): TracerInterface
    {
        $factory = new TracerProviderFactory();
        $tracerProvider = $factory->create();

        return $tracerProvider->getTracer('application');
    }
}
