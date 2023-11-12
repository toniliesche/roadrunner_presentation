<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Infrastructure\Log\Factories;

use Monolog\Formatter\FormatterInterface;
use Monolog\Formatter\HtmlFormatter;
use Monolog\Level;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Log\LoggerInterface;
use ToniLiesche\Roadrunner\Core\Application\Framework\Enums\LogType;
use ToniLiesche\Roadrunner\Core\Application\Framework\Models\Config;
use ToniLiesche\Roadrunner\Core\Application\Framework\Models\LogConfig;
use ToniLiesche\Roadrunner\Infrastructure\Log\Interfaces\ApplicationLoggerInterface;
use ToniLiesche\Roadrunner\Infrastructure\Log\Services\ApplicationLogger;

readonly final class ApplicationLoggerFactory extends AbstractLoggerFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ApplicationLoggerInterface
    {
        $config = $container->get(Config::class);
        $logConfig = $config->getFrameworkConfig()->getLogConfig();

        if (LogType::DEV === $logConfig->getLogType()) {
            $file = \sprintf('%s/%s.html', $logConfig->getLogPath(), $config->getApplicationConfig()->getName());
        } else {
            $file = \sprintf('%s/%s.log', $logConfig->getLogPath(), $config->getApplicationConfig()->getName());
        }

        $formatter = $this->createFormatter(
            $logConfig,
            $logConfig->getLogType(),
            "[%datetime%] %level_name%: %message%\n",
            'Y-m-d H:i:s'
        );

        return new ApplicationLogger(
            $this->createLogger(
                $config->getApplicationConfig()->getName(),
                $file,
                Level::fromName($logConfig->getLogLevel()),
                $formatter
            ),
            $this->createMessageProcessor($container, $logConfig->getLogType()),
            $this->createContextProcessor($container, $logConfig->getLogType()),
        );
    }
}
