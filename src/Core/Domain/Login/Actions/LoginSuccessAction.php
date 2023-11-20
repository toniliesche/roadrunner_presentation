<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Domain\Login\Actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ToniLiesche\Roadrunner\Core\Application\Framework\Actions\AbstractFrontendAction;

readonly class LoginSuccessAction extends AbstractFrontendAction
{
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {

    }
}
