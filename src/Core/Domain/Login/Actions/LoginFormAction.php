<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Domain\Login\Actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ToniLiesche\Roadrunner\Core\Application\Framework\Actions\AbstractFrontendAction;
use ToniLiesche\Roadrunner\Infrastructure\Http\Enums\HttpStatus;

readonly final class LoginFormAction extends AbstractFrontendAction
{
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        return $this->renderer->renderTemplate($request, HttpStatus::OK, 'login/form');
    }
}
