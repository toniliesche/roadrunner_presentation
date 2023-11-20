<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Domain\Shared\Actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ToniLiesche\Roadrunner\Core\Application\Framework\Actions\AbstractAPIAction;
use ToniLiesche\Roadrunner\Core\Application\Library\Exceptions\DataConversionException;
use ToniLiesche\Roadrunner\Core\Application\Library\Exceptions\UnexpectedValueException;
use ToniLiesche\Roadrunner\Infrastructure\Http\Enums\HttpStatus;
use ToniLiesche\Roadrunner\Infrastructure\Log\Logging;

final readonly class PingAction extends AbstractAPIAction
{
    /**
     * @throws UnexpectedValueException
     * @throws DataConversionException
     */
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        Logging::audit()?->log('Accessing ping page.');

        return $this->renderer->renderResponse($request, HttpStatus::NO_CONTENT);
    }
}
