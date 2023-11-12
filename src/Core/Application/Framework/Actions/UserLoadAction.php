<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Framework\Actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ToniLiesche\Roadrunner\Core\Application\Framework\Traits\RequestParserAwareTrait;
use ToniLiesche\Roadrunner\Core\Domain\Users\Interfaces\UserServiceInterface;
use ToniLiesche\Roadrunner\Infrastructure\Log\Interfaces\AuditLoggerInterface;

final class UserLoadAction
{
    use RequestParserAwareTrait;

    public function __construct(
        private readonly AuditLoggerInterface $auditLogger,
        private readonly UserServiceInterface $userService
    ) {
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $userId = $this->getRequestParser()->getNumericQueryParam($request, 'userId');
        $this->auditLogger->log('Loading user information.', ['userId' => $userId]);

        $user = $this->userService->getUser($userId);

        return $response;
    }
}
