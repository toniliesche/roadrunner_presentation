<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Framework\Models;

use ToniLiesche\Roadrunner\Core\Application\Framework\Exceptions\ComponentNotConfiguredException;
use ToniLiesche\Roadrunner\Core\Application\Framework\Exceptions\MissingConfigValueException;

readonly final class FrameworkConfig
{
    private DIConfig $diConfig;

    private LogConfig $logConfig;

    private RouterConfig $routerConfig;

    private TemplatingConfig $templatingConfig;

    /**
     * @throws MissingConfigValueException
     */
    public function __construct(array $data = [])
    {
        if (!isset($data['di'])) {
            throw new MissingConfigValueException('Missing mandatory section "di" in framework config section');
        }
        if (!isset($data['router'])) {
            throw new MissingConfigValueException('Missing mandatory section "router" in framework config section');
        }

        $this->diConfig = new DIConfig($data['di']);
        $this->logConfig = new LogConfig($data['log'] ?? []);
        $this->routerConfig = new RouterConfig($data['router']);

        if (isset($data['templating'])) {
            $this->templatingConfig = new TemplatingConfig($data['templating']);
        }
    }

    public function getDiConfig(): DIConfig
    {
        return $this->diConfig;
    }

    public function getLogConfig(): LogConfig
    {
        return $this->logConfig;
    }

    public function getRouterConfig(): RouterConfig
    {
        return $this->routerConfig;
    }

    /**
     * @throws ComponentNotConfiguredException
     */
    public function getTemplatingConfig(): TemplatingConfig
    {
        return $this->templatingConfig ?? throw new ComponentNotConfiguredException('Templating engine has not been configured in framework config section.');
    }
}
