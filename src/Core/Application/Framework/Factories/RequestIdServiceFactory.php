<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Framework\Factories;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use ToniLiesche\Roadrunner\Core\Application\Framework\Interfaces\UuidServiceInterface;
use ToniLiesche\Roadrunner\Core\Application\Framework\Models\Config;
use ToniLiesche\Roadrunner\Core\Application\Framework\Services\RequestIdService;
use ToniLiesche\Roadrunner\Infrastructure\Engine\Services\RoadrunnerRequestCleaningService;

class RequestIdServiceFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): RequestIdService
    {
        $config = $container->get(Config::class);

        $service = new RequestIdService(
            $config->getApplicationConfig()->getName(),
            $container->get(UuidServiceInterface::class),
            \sprintf('HTTP_%s', \strtoupper(\str_replace('-', '_', $config->getSystemConfig()->getReferralHeader())))
        );
        $service->generateRequestId();

        $roadrunnerRequestCleaningService = $container->get(RoadrunnerRequestCleaningService::class);
        $roadrunnerRequestCleaningService->registerBeforeRequest($service);
        $roadrunnerRequestCleaningService->registerAfterRequest($service);

        return $service;
    }
}
