<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Framework\Interfaces;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ToniLiesche\Roadrunner\Infrastructure\Http\Enums\HttpPhrase;

interface ApiResponseRendererInterface
{
    public function renderResponse(ServerRequestInterface $request, HttpPhrase $status, array $payload = []): ResponseInterface;
}
