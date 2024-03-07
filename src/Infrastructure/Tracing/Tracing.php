<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Infrastructure\Tracing;

use OpenTelemetry\API\Trace\SpanInterface;
use OpenTelemetry\API\Trace\TracerInterface;
use OpenTelemetry\Context\ScopeInterface;
use OpenTelemetry\SDK\Trace\TracerProviderFactory;
use OpenTelemetry\SDK\Trace\TracerProviderInterface;
use Psr\Container\ContainerInterface;

class Tracing
{
    private static TracerProviderFactory $factory;

    private static TracerProviderInterface $tracerProvider;

    private static TracerInterface $requestTracer;

    private static SpanInterface $span;

    private static ScopeInterface $spanScope;

    public static function init(ContainerInterface $container): void
    {
        self::$factory = new TracerProviderFactory();
    }

    public static function start(string $span): void
    {
        self::$tracerProvider = self::$factory->create();
        self::$requestTracer = self::$tracerProvider->getTracer('application');

        self::$span = self::$requestTracer
            ->spanBuilder($span)
            ->startSpan();
        self::$spanScope = self::$span->activate();
    }

    public static function finish(): void
    {
        self::$spanScope?->detach();
        self::$span?->end();
        self::$tracerProvider?->shutdown();
    }

    public static function addEvent(string $message, iterable $attributes = [], int $timestamp = null): void
    {
        self::$span->addEvent($message, $attributes, $timestamp);
    }
}
