<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Framework\Services;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use ToniLiesche\Roadrunner\Core\Application\View\Interfaces\ErrorResponseRendererInterface;
use ToniLiesche\Roadrunner\Infrastructure\Http\Exceptions\HttpException;

readonly class ErrorHandler
{
    public function __construct(private ErrorResponseRendererInterface $apiErrorRenderer, private ?ErrorResponseRendererInterface $templateErrorRenderer = null)
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

        if ($t instanceof HttpException) {
            if ($t->getApiResult()) {
                $renderer = $this->apiErrorRenderer;
            } else {
                $renderer = $this->templateErrorRenderer ?? $this->apiErrorRenderer;
            }
        } else {
            $renderer = $this->apiErrorRenderer;
        }

        return $renderer->renderError($request, $t);
    }
}
