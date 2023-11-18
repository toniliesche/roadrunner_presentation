<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Framework\Services;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use ToniLiesche\Roadrunner\Core\Application\Framework\Interfaces\ErrorResponseRendererInterface;

readonly class ErrorHandler
{
    public function __construct(private ErrorResponseRendererInterface $renderer)
    {
    }

    public function __invoke(
        ServerRequestInterface $request,
        \Throwable $t,
        bool $displayErrorDetails,
        bool $logErrors,
        bool $logErrorDetails,
        ?LoggerInterface $logger = null
    ): ResponseInterface {
        if (!\is_null($logger)) {
            $logger->error('Uncaught exeption:');
        }

        return $this->renderer->renderError($request, $t);
    }
}
