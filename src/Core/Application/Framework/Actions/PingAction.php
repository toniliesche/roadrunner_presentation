<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Framework\Actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ToniLiesche\Roadrunner\Infrastructure\Log\Interfaces\AuditLoggerInterface;

final readonly class PingAction
{
    public function __construct(private AuditLoggerInterface $auditLogger)
    {
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $this->auditLogger->log('Accessing ping page.');

        return $response;
    }
}
