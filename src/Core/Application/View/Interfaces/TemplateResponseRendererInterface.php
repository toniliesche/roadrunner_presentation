<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\View\Interfaces;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ToniLiesche\Roadrunner\Infrastructure\Http\Enums\HttpStatus;

interface TemplateResponseRendererInterface extends ErrorResponseRendererInterface
{
    public function renderTemplate(ServerRequestInterface $request, HttpStatus $status, string $template, array $data = []): ResponseInterface;
}
