<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Domain\Test\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use ToniLiesche\Roadrunner\Core\Domain\Test\Interfaces\UserServiceInterface;

readonly final class UserService implements UserServiceInterface
{
    public function __construct(private Client $client)
    {
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function getUser(string $host, int $userId): ResponseInterface
    {
        $uri = \sprintf('%s/user?userId=%d', $host, $userId);
        $request = new Request('GET', $uri);

        return $this->client->sendRequest($request);
    }

    public function getUserAsync(string $host, int $userId): PromiseInterface
    {
        $uri = \sprintf('%s/user?userId=%d', $host, $userId);
        $request = new Request('GET', $uri);

        return $this->client->sendAsync($request);
    }
}
