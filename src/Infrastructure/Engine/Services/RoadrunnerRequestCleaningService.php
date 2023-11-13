<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Infrastructure\Engine\Services;

use ToniLiesche\Roadrunner\Infrastructure\Engine\Interfaces\RunAfterRequestInterface;
use ToniLiesche\Roadrunner\Infrastructure\Engine\Interfaces\RunBeforeRequestInterface;
use ToniLiesche\Roadrunner\Infrastructure\Engine\Interfaces\RunOnWarmupInterface;
use ToniLiesche\Roadrunner\Infrastructure\Log\Enums\LogCategory;
use ToniLiesche\Roadrunner\Infrastructure\Log\Logging;

class RoadrunnerRequestCleaningService
{
    /** @var RunOnWarmupInterface[] */
    private array $onWarmup = [];

    /** @var RunBeforeRequestInterface[] */
    private array $beforeRequest = [];

    /** @var RunAfterRequestInterface[] */
    private array $afterRequest = [];

    public function registerOnWarmup(RunOnWarmupInterface $service): void
    {
        Logging::application()?->debug(LogCategory::FRAMEWORK, \sprintf('Registering new on warmup request task %s', \get_class($service)));
        $this->onWarmup[] = $service;
    }

    public function registerBeforeRequest(RunBeforeRequestInterface $service): void
    {
        Logging::application()?->debug(LogCategory::FRAMEWORK, \sprintf('Registering new before request task %s', \get_class($service)));
        $this->beforeRequest[] = $service;
    }

    public function registerAfterRequest(RunAfterRequestInterface $service): void
    {
        Logging::application()?->debug(LogCategory::FRAMEWORK, \sprintf('Registering new after request task %s', \get_class($service)));
        $this->afterRequest[] = $service;
    }

    public function processOnWarmup(): void
    {
        foreach ($this->onWarmup as $service) {
            Logging::application()?->debug(LogCategory::FRAMEWORK, \sprintf('Running warmup on %s', \get_class($service)));
            $service->runWarmup();
        }
    }

    public function processBeforeRequest(): void
    {
        foreach ($this->beforeRequest as $service) {
            Logging::application()?->debug(LogCategory::FRAMEWORK, \sprintf('Running before request task on %s', \get_class($service)));
            $service->runBeforeRequest();
        }
    }

    public function processAfterRequest(): void
    {
        foreach ($this->afterRequest as $service) {
            Logging::application()?->debug(LogCategory::FRAMEWORK, \sprintf('Running after request task on %s', \get_class($service)));
            $service->runAfterRequest();
        }
    }
}
