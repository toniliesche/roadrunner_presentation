<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Framework\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Middleware\Session;

final class SessionMiddleware extends Session
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $response = parent::__invoke($request, $handler);

        if (session_status() !== PHP_SESSION_NONE) {
            session_write_close();
        }

        return $response;
    }
}
