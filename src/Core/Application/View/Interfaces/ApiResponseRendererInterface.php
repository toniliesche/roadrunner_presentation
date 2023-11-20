<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\View\Interfaces;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ToniLiesche\Roadrunner\Infrastructure\Http\Enums\HttpStatus;

interface ApiResponseRendererInterface extends ErrorResponseRendererInterface
{
    public function renderResponse(ServerRequestInterface $request, HttpStatus $status, array $payload = []): ResponseInterface;
}
