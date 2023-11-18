<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Framework\Actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ToniLiesche\Roadrunner\Core\Application\Framework\Exceptions\DataConversionException;
use ToniLiesche\Roadrunner\Core\Application\Framework\Exceptions\UnexpectedValueException;
use ToniLiesche\Roadrunner\Core\Application\Framework\Services\Renderers\ApiResponseRenderer;
use ToniLiesche\Roadrunner\Infrastructure\Http\Enums\HttpPhrase;
use ToniLiesche\Roadrunner\Infrastructure\Log\Logging;

final readonly class PingAction
{
    public function __construct(private ApiResponseRenderer $renderer)
    {
    }

    /**
     * @throws UnexpectedValueException
     * @throws DataConversionException
     */
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        Logging::audit()?->log('Accessing ping page.');

        return $this->renderer->renderResponse($request, HttpPhrase::NO_CONTENT);
    }
}
