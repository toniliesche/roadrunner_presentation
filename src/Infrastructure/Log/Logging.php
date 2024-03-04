<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Infrastructure\Log;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use ToniLiesche\Roadrunner\Infrastructure\Log\Interfaces\ApplicationLoggerInterface;
use ToniLiesche\Roadrunner\Infrastructure\Log\Interfaces\AuditLoggerInterface;
use ToniLiesche\Roadrunner\Infrastructure\Log\Interfaces\SqlLoggerInterface;
use ToniLiesche\Roadrunner\Infrastructure\Log\Services\WrappedLogger;

class Logging
{
    private static ?ApplicationLoggerInterface $applicationLogger = null;

    private static ?AuditLoggerInterface $auditLogger = null;

    private static ?LoggerInterface $sqlLogger = null;

    private static ?LoggerInterface $psrLogger = null;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public static function init(ContainerInterface $container): void
    {
        self::$applicationLogger = $container->get(ApplicationLoggerInterface::class);
        self::$auditLogger = $container->get(AuditLoggerInterface::class);
        self::$sqlLogger = $container->get(SqlLoggerInterface::class);
    }

    public static function application(): ?ApplicationLoggerInterface
    {
        return self::$applicationLogger ?? null;
    }

    public static function psr(): LoggerInterface
    {
        if (!isset(self::$psrLogger) || self::$psrLogger instanceof NullLogger) {
            if (isset(self::$applicationLogger)) {
                self::$psrLogger = new WrappedLogger(self::$applicationLogger);
            }

            if (!isset(self::$psrLogger)) {
                self::$psrLogger = new NullLogger();
            }
        }

        return self::$psrLogger;
    }

    public static function audit(): ?AuditLoggerInterface
    {
        return self::$auditLogger ?? null;
    }

    public static function sql(): ?LoggerInterface
    {
        return self::$sqlLogger ?? null;
    }
}
