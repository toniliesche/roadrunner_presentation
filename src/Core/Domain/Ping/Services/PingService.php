<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Domain\Ping\Services;

use GuzzleHttp\Psr7\Request;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use ToniLiesche\Roadrunner\Core\Domain\Ping\Interfaces\PingServiceInterface;

final readonly class PingService implements PingServiceInterface
{
    public function __construct(private ClientInterface $client)
    {
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function ping(string $host): void
    {
//        $uri = \sprintf('%s/_internal/ping', $host);
        $uri = \sprintf('%s/user?userId=1', $host);
        $request = new Request('GET', $uri);

        $response = $this->client->sendRequest($request);
    }
}
