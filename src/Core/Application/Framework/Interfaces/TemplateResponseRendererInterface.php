<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Framework\Interfaces;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ToniLiesche\Roadrunner\Infrastructure\Http\Enums\HttpPhrase;

interface TemplateResponseRendererInterface
{
    public function renderTemplate(ServerRequestInterface $request, HttpPhrase $status, string $template, array $data = []): ResponseInterface;
}
