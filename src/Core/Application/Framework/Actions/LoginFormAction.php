<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Framework\Actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ToniLiesche\Roadrunner\Core\Application\Framework\Interfaces\TemplateResponseRendererInterface;
use ToniLiesche\Roadrunner\Infrastructure\Http\Enums\HttpPhrase;

readonly final class LoginFormAction
{
    public function __construct(private TemplateResponseRendererInterface $renderer)
    {

    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        return $this->renderer->renderTemplate($request, HttpPhrase::OK, 'login/form');
    }
}
