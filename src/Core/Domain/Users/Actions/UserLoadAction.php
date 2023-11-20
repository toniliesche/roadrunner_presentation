<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Domain\Users\Actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ToniLiesche\Roadrunner\Core\Application\View\Interfaces\ApiResponseRendererInterface;
use ToniLiesche\Roadrunner\Core\Domain\Users\Interfaces\UserServiceInterface;
use ToniLiesche\Roadrunner\Core\Domain\Users\Services\UserMapper;
use ToniLiesche\Roadrunner\Infrastructure\Http\Enums\HttpStatus;
use ToniLiesche\Roadrunner\Infrastructure\Http\Traits\RequestParserAwareTrait;
use ToniLiesche\Roadrunner\Infrastructure\Log\Logging;

final class UserLoadAction
{
    use RequestParserAwareTrait;

    public function __construct(
        private readonly ApiResponseRendererInterface $renderer,
        private readonly UserServiceInterface $userService
    ) {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $userId = $this->getRequestParser()->getNumericQueryParam($request, 'userId');
        Logging::audit()?->log('Loading user information.', ['userId' => $userId]);

        $user = $this->userService->fetchUser($userId);

        return $this->renderer->renderResponse($request, HttpStatus::OK, UserMapper::modelToArray($user));
    }
}
