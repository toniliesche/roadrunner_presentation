<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Framework\Actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ToniLiesche\Roadrunner\Core\Application\Framework\Interfaces\ApiResponseRendererInterface;
use ToniLiesche\Roadrunner\Core\Application\Framework\Traits\RequestParserAwareTrait;
use ToniLiesche\Roadrunner\Core\Domain\Users\Interfaces\UserServiceInterface;
use ToniLiesche\Roadrunner\Core\Domain\Users\Services\UserMapper;
use ToniLiesche\Roadrunner\Infrastructure\Http\Enums\HttpPhrase;
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

        $user = $this->userService->getUser($userId);

        return $this->renderer->renderResponse($request, HttpPhrase::OK, UserMapper::modelToArray($user));
    }
}
