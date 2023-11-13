<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Domain\Test\Factories;

use GuzzleHttp\Client;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use ToniLiesche\Roadrunner\Core\Domain\Test\Services\UserService;

final readonly class UserServiceFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): UserService
    {
        return new UserService($container->get(Client::class));
    }
}
