<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Domain\Test\Interfaces;

use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\ResponseInterface;

interface PingServiceInterface
{
    public function ping(string $host): ResponseInterface;

    public function pingAsync(string $host): PromiseInterface;
}
