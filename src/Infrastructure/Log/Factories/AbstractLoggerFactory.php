<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Infrastructure\Log\Factories;

use Monolog\Formatter\FormatterInterface;
use Monolog\Formatter\HtmlFormatter;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Monolog\Processor\PsrLogMessageProcessor;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Log\LoggerInterface;
use ToniLiesche\Roadrunner\Core\Application\Framework\Enums\LogType;
use ToniLiesche\Roadrunner\Core\Application\Framework\Models\LogConfig;
use ToniLiesche\Roadrunner\Infrastructure\Log\Interfaces\ContextProcessorInterface;
use ToniLiesche\Roadrunner\Infrastructure\Log\Interfaces\MessageProcessorInterface;
use ToniLiesche\Roadrunner\Infrastructure\Log\Services\ContextProcessors\BaseContextProcessor;
use ToniLiesche\Roadrunner\Infrastructure\Log\Services\ContextProcessors\GraylogContextProcessor;
use ToniLiesche\Roadrunner\Infrastructure\Log\Services\Formatters\GelfFormatter;
use ToniLiesche\Roadrunner\Infrastructure\Log\Services\LogEntryContextProvider;
use ToniLiesche\Roadrunner\Infrastructure\Log\Services\MessageProcessors\BaseMessageProcessor;
use ToniLiesche\Roadrunner\Infrastructure\Log\Services\MessageProcessors\MockMessageProcessor;

readonly abstract class AbstractLoggerFactory
{
    protected function createLogger(
        string $loggerName,
        string $file,
        Level $logLevel,
        FormatterInterface $formatter
    ): LoggerInterface {
        $logger = new Logger($loggerName);

        $handler = new StreamHandler($file, $logLevel);
        $handler->setFormatter($formatter);
        $logger->pushHandler($handler);

        $processor = new PsrLogMessageProcessor();
        $logger->pushProcessor($processor);

        return $logger;
    }

    protected function createFormatter(LogConfig $config, LogType $logType, ?string $lineFormat = null, ?string $dateFormat = null): FormatterInterface
    {
        return match ($logType) {
            LogType::GRAYLOG => new GelfFormatter(prettyPrint: $config->isDebugEnabled()),
            LogType::DEV => new HtmlFormatter($dateFormat),
            default => new LineFormatter($lineFormat, $dateFormat),
        };
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function createMessageProcessor(ContainerInterface $container, LogType $logType): MessageProcessorInterface {
        return match ($logType) {
            LogType::GRAYLOG, LogType::DEV => new MockMessageProcessor(),
            default => new BaseMessageProcessor($container->get(LogEntryContextProvider::class)),
        };
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function createContextProcessor(ContainerInterface $container, LogType $logType): ContextProcessorInterface {
        $contextProvider = $container->get(LogEntryContextProvider::class);

        return match ($logType) {
            LogType::GRAYLOG => new GraylogContextProcessor($contextProvider),
            default => new BaseContextProcessor($contextProvider),
        };
    }
}
