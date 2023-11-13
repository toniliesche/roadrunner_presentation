<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Domain\Test\Interfaces;

use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\ResponseInterface;

interface UserServiceInterface
{
    public function getUser(string $host, int $userId): ResponseInterface;

    public function getUserAsync(string $host, int $userId): PromiseInterface;
}
