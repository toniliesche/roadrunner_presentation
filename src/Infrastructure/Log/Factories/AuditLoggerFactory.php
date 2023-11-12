<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Infrastructure\Log\Factories;

use Monolog\Level;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use ToniLiesche\Roadrunner\Core\Application\Framework\Enums\LogType;
use ToniLiesche\Roadrunner\Core\Application\Framework\Models\Config;
use ToniLiesche\Roadrunner\Infrastructure\Log\Interfaces\AuditLoggerInterface;
use ToniLiesche\Roadrunner\Infrastructure\Log\Services\AuditLogger;

readonly final class AuditLoggerFactory extends AbstractLoggerFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): AuditLoggerInterface
    {
        $config = $container->get(Config::class);
        $logConfig = $config->getFrameworkConfig()->getLogConfig();

        if (LogType::DEV === $logConfig->getLogType()) {
            $file = \sprintf('%s/%s_audit.html', $logConfig->getLogPath(), $config->getApplicationConfig()->getName());
        } else {
            $file = \sprintf('%s/%s_audit.log', $logConfig->getLogPath(), $config->getApplicationConfig()->getName());
        }
        $formatter = $this->createFormatter(
            $logConfig,
            $logConfig->getLogType(),
            "[%datetime%] %message%\n",
            'Y-m-d H:i:s'
        );

        return new AuditLogger(
            $this->createLogger(
                \sprintf('%s-audit', $config->getApplicationConfig()->getName()),
                $file,
                Level::Info,
                $formatter
            ),
            $this->createMessageProcessor($container, $logConfig->getLogType()),
            $this->createContextProcessor($container, $logConfig->getLogType()),
        );
    }
}
