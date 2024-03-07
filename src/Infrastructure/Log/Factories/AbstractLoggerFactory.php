<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Infrastructure\Log\Factories;

use Monolog\Formatter\FormatterInterface;
use Monolog\Formatter\HtmlFormatter;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Monolog\Processor\PsrLogMessageProcessor;
use OpenTelemetry\API\Globals;
use OpenTelemetry\Contrib\Logs\Monolog\Handler;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Log\LoggerInterface;
use RoadRunner\Logger\Logger as RPCLogger;
use Spiral\Goridge\RPC\RPC;
use ToniLiesche\Roadrunner\Core\Application\Config\Models\LogConfig;
use ToniLiesche\Roadrunner\Core\Application\Library\Enums\LogType;
use ToniLiesche\Roadrunner\Infrastructure\Log\Interfaces\ContextProcessorInterface;
use ToniLiesche\Roadrunner\Infrastructure\Log\Interfaces\MessageProcessorInterface;
use ToniLiesche\Roadrunner\Infrastructure\Log\Services\ContextProcessors\BaseContextProcessor;
use ToniLiesche\Roadrunner\Infrastructure\Log\Services\ContextProcessors\GraylogContextProcessor;
use ToniLiesche\Roadrunner\Infrastructure\Log\Services\Formatters\GelfFormatter;
use ToniLiesche\Roadrunner\Infrastructure\Log\Services\LogEntryContextProvider;
use ToniLiesche\Roadrunner\Infrastructure\Log\Services\MessageProcessors\BaseMessageProcessor;
use ToniLiesche\Roadrunner\Infrastructure\Log\Services\MessageProcessors\MockMessageProcessor;
use ToniLiesche\Roadrunner\Infrastructure\Log\Wrappers\RoadRunnerLogger;

readonly abstract class AbstractLoggerFactory
{
    protected function createFileLogger(
        string $loggerName,
        string $file,
        Level $logLevel,
        FormatterInterface $formatter
    ): LoggerInterface {
        $handler = new StreamHandler($file, $logLevel);

        return $this->createLogger($loggerName, $handler, $formatter);
    }

    protected function createOtelLogger(
        string $loggerName,
        Level $logLevel,
    ): LoggerInterface {
        $loggerProvider = Globals::loggerProvider();
        $handler = new Handler($loggerProvider, $logLevel);

        return $this->createLogger($loggerName, $handler, null);
    }

    protected function createRRLogger(RPC $rpc): LoggerInterface
    {
        return new RoadRunnerLogger(new RPCLogger($rpc));
    }

    protected function createFormatter(
        LogConfig $config,
        LogType $logType,
        ?string $lineFormat = null,
        ?string $dateFormat = null
    ): FormatterInterface {
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
    protected function createMessageProcessor(
        ContainerInterface $container,
        LogType $logType
    ): MessageProcessorInterface {
        return match ($logType) {
            LogType::GRAYLOG, LogType::DEV => new MockMessageProcessor(),
            default => new BaseMessageProcessor($container->get(LogEntryContextProvider::class)),
        };
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function createContextProcessor(
        ContainerInterface $container,
        LogType $logType
    ): ContextProcessorInterface {
        $contextProvider = $container->get(LogEntryContextProvider::class);

        return match ($logType) {
            LogType::GRAYLOG => new GraylogContextProcessor($contextProvider),
            default => new BaseContextProcessor($contextProvider),
        };
    }

    private function createLogger(
        string $loggerName,
        AbstractProcessingHandler $handler,
        ?FormatterInterface $formatter
    ): LoggerInterface {
        $logger = new Logger($loggerName);

        if (isset($formatter)) {
            $handler->setFormatter($formatter);
        }
        $logger->pushHandler($handler);

        $processor = new PsrLogMessageProcessor();
        $logger->pushProcessor($processor);

        return $logger;
    }
}
