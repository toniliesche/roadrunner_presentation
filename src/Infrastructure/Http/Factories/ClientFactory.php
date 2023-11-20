<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Infrastructure\Http\Factories;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use ToniLiesche\Roadrunner\Core\Application\Config\Models\Config;
use ToniLiesche\Roadrunner\Core\Application\Framework\Services\RequestIdService;
use ToniLiesche\Roadrunner\Infrastructure\Http\Middlewares\ReferralHeaderMiddleware;

readonly final class ClientFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): Client
    {
        $config = $container->get(Config::class);

        $handler = new CurlHandler();
        $stack = HandlerStack::create($handler);
        $stack->push($this->createReferralHeaderMiddleware($container, $config));

        return new Client(
            [
                'handler' => $stack,
                'headers' => ['User-Agent' => $this->getUserAgent($config)]
            ]
        );
    }

    private function getUserAgent(Config $config): string
    {
        return \sprintf(
            '%s/%s-GuzzleHttp/%d',
            $config->getApplicationConfig()->getName(),
            $config->getApplicationConfig()->getVersion(),
            ClientInterface::MAJOR_VERSION
        );
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function createReferralHeaderMiddleware(
        ContainerInterface $container,
        Config $config
    ): ReferralHeaderMiddleware {
        $requestIdService = $container->get(RequestIdService::class);

        $referralHeader = $config->getSystemConfig()->getReferralHeader();

        return new ReferralHeaderMiddleware($referralHeader, fn () => $requestIdService->getReferralId());
    }
}
