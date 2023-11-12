<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Framework\Services;

use Psr\Http\Message\ServerRequestInterface;
use ToniLiesche\Roadrunner\Core\Application\Framework\Interfaces\UuidServiceInterface;
use ToniLiesche\Roadrunner\Infrastructure\Engine\Interfaces\RunAfterRequestInterface;
use ToniLiesche\Roadrunner\Infrastructure\Engine\Interfaces\RunBeforeRequestInterface;

class RequestIdService implements RunBeforeRequestInterface, RunAfterRequestInterface
{
    private string $requestId;

    private string $referralId;

    public function __construct(private readonly string $application, private readonly UuidServiceInterface $uuidService, private readonly string $referralIdHeader)
    {
    }

    public function getRequestId(): string
    {
        return $this->requestId ?? $this->generateRequestId();
    }

    public function generateRequestId(): string
    {
        $this->requestId = \sprintf('%s-%s', $this->application, $this->uuidService->getUuid());
        return $this->requestId;
    }

    public function getReferralId(): string
    {
        return $this->referralId ?? $this->getRequestId();
    }

    public function setReferralId(string $referralId): void
    {
        $this->referralId = $referralId;
    }

    public function runBeforeRequest(): void
    {
        $this->generateRequestId();
    }

    public function parseReferralId(ServerRequestInterface $request): void
    {
        $serverParams = $request->getServerParams();
        if (!isset($serverParams[$this->referralIdHeader])) {
            return;
        }

        $referralIdHeaders = $serverParams[$this->referralIdHeader];
        if (\is_array($referralIdHeaders) && !empty($referralIdHeaders)) {
            $this->referralId = $referralIdHeaders[0];
            return;
        }

        if (\is_string($referralIdHeaders) && !empty($referralIdHeaders)) {
            $this->referralId = $referralIdHeaders;
        }
    }

    public function runAfterRequest(): void
    {
        unset($this->requestId, $this->referralId);
    }
}
