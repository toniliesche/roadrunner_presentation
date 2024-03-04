<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Infrastructure\Metrics\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Routing\RouteContext;
use Throwable;
use ToniLiesche\Roadrunner\Infrastructure\Http\Exceptions\HttpException;
use ToniLiesche\Roadrunner\Infrastructure\Metrics\Services\MetricsService;

readonly final class RequestMetricsMiddleware implements MiddlewareInterface
{
    public function __construct(private MetricsService $metricsService)
    {
    }

    /**
     * @throws Throwable
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();

        try {
            $response = $handler->handle($request);
        } catch (HttpException $ex) {
            $this->metricsService->trackRequest($route?->getPattern() ?? 'unknown-route', $request->getMethod(), $ex->getCode());
            throw $ex;
        } catch (Throwable $t) {
            $this->metricsService->trackRequest($route?->getPattern() ?? 'unknown-route', $request->getMethod(),  503);
            throw $t;
        }

        $this->metricsService->trackRequest($route?->getPattern() ?? 'unknown-route', $request->getMethod(), $response->getStatusCode());

        return $response;
    }
}
