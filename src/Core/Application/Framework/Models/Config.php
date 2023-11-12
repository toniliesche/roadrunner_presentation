<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Framework\Models;

use ToniLiesche\Roadrunner\Core\Application\Framework\Enums\PHPRuntime;
use ToniLiesche\Roadrunner\Core\Application\Framework\Exceptions\MissingConfigValueException;

readonly final class Config
{
    private DatabaseConfig $databaseConfig;
    
    private FrameworkConfig $frameworkConfig;

    private ApplicationConfig $applicationConfig;

    private SystemConfig $systemConfig;

    /**
     * @throws MissingConfigValueException
     */
    public function __construct(array $data = [])
    {
        if (!isset($data['framework'])) {
            throw new MissingConfigValueException('Missing mandatory section "framework" in application config');
        }

        $this->frameworkConfig = new FrameworkConfig($data['framework']);

        if (isset($data['database'])) {
            $this->databaseConfig = new DatabaseConfig($data['database']);
        }

        $this->applicationConfig = new ApplicationConfig($data['application'] ?? []);
        $this->systemConfig = new SystemConfig($data['system'] ?? []);
    }

    public function hasDatabaseConfig(): bool
    {
        return isset($this->databaseConfig);
    }

    public function getDatabaseConfig(): DatabaseConfig
    {
        return $this->databaseConfig;
    }

    public function getFrameworkConfig(): FrameworkConfig
    {
        return $this->frameworkConfig;
    }

    public function getApplicationConfig(): ApplicationConfig
    {
        return $this->applicationConfig;
    }

    public function getSystemConfig(): SystemConfig
    {
        return $this->systemConfig;
    }
}
