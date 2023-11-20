<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Infrastructure\Log\Factories;

use Monolog\Level;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use ToniLiesche\Roadrunner\Core\Application\Config\Models\Config;
use ToniLiesche\Roadrunner\Core\Application\Library\Enums\LogType;
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
