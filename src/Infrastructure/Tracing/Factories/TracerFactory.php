<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Infrastructure\Tracing\Factories;

use OpenTelemetry\API\Globals;
use OpenTelemetry\API\Trace\TracerInterface;
use Psr\Container\ContainerInterface;

class TracerFactory
{
    public function __invoke(ContainerInterface $container): TracerInterface
    {
        return Globals::tracerProvider()->getTracer('application');
    }
}
