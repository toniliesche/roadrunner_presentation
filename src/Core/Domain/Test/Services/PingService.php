<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Domain\Test\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use ToniLiesche\Roadrunner\Core\Domain\Test\Interfaces\PingServiceInterface;

readonly final class PingService implements PingServiceInterface
{
    public function __construct(private Client $client)
    {
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function ping(string $host): ResponseInterface
    {
        $uri = \sprintf('%s/_internal/ping', $host);
        $request = new Request('GET', $uri);

        return $this->client->sendRequest($request);
    }

    public function pingAsync(string $host): PromiseInterface
    {
        $uri = \sprintf('%s/_internal/ping', $host);
        $request = new Request('GET', $uri);

        return $this->client->sendAsync($request);
    }
}
