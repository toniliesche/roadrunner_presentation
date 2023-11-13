<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Domain\Test\Factories;

use GuzzleHttp\Client;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use ToniLiesche\Roadrunner\Core\Domain\Test\Services\PingService;

final readonly class PingServiceFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): PingService
    {
        return new PingService($container->get(Client::class));
    }
}
