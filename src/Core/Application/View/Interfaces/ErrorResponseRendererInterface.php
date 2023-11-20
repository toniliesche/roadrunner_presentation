<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\View\Interfaces;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

interface ErrorResponseRendererInterface
{
    public function renderError(ServerRequestInterface $request, Throwable $t): ResponseInterface;
}
