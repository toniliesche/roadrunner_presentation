<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Infrastructure\Log\Factories;

use Monolog\Level;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Log\LoggerInterface;
use ToniLiesche\Roadrunner\Core\Application\Config\Models\Config;
use ToniLiesche\Roadrunner\Core\Application\Library\Enums\LogType;
use ToniLiesche\Roadrunner\Infrastructure\Log\Services\SqlLogger;

readonly final class SqlLoggerFactory extends AbstractLoggerFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): LoggerInterface
    {
        $config = $container->get(Config::class);
        $logConfig = $config->getFrameworkConfig()->getLogConfig();

        if (LogType::DEV === $logConfig->getLogType()) {
            $file = \sprintf('%s/%s_sql.html', $logConfig->getLogPath(), $config->getApplicationConfig()->getName());
        } else {
            $file = \sprintf('%s/%s_sql.log', $logConfig->getLogPath(), $config->getApplicationConfig()->getName());
        }
        $formatter = $this->createFormatter(
            $logConfig,
            $logConfig->getLogType(),
            "[%datetime%] %message%\n",
            'Y-m-d H:i:s'
        );

        return new SqlLogger(
            $this->createLogger(
                'sql',
                $file,
                Level::Debug,
                $formatter
            ),
            $this->createMessageProcessor($container, $logConfig->getLogType()),
            $this->createContextProcessor($container, $logConfig->getLogType()),
        );
    }
}
