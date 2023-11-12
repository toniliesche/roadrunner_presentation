<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Framework\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use ToniLiesche\Roadrunner\Core\Application\Framework\Services\RequestIdService;

readonly class RequestIdMiddleware implements MiddlewareInterface
{
    /**
     * RequestIdMiddleware constructor
     *
     * @param RequestIdService $requestIdService
     */
    public function __construct(private RequestIdService $requestIdService)
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->requestIdService->generateRequestId();
        $this->requestIdService->parseReferralId($request);

        return $handler->handle($request);
    }
}
