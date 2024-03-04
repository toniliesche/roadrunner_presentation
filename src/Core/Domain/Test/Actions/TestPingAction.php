<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Domain\Test\Actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Stopwatch\Stopwatch;
use ToniLiesche\Roadrunner\Core\Application\Framework\Actions\AbstractAPIAction;
use ToniLiesche\Roadrunner\Core\Application\Library\Exceptions\DataConversionException;
use ToniLiesche\Roadrunner\Core\Application\Library\Exceptions\UnexpectedValueException;
use ToniLiesche\Roadrunner\Core\Application\View\Services\ApiResponseRenderer;
use ToniLiesche\Roadrunner\Core\Domain\Test\Interfaces\PingServiceInterface;
use ToniLiesche\Roadrunner\Infrastructure\Http\Enums\HttpStatus;
use ToniLiesche\Roadrunner\Infrastructure\Log\Logging;

readonly final class TestPingAction extends AbstractAPIAction
{
    public function __construct(private PingServiceInterface $pingService, ApiResponseRenderer $renderer)
    {
        parent::__construct($renderer);
    }

    /**
     * @throws DataConversionException
     * @throws UnexpectedValueException
     */
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $stopWatch = new Stopwatch();
        $stopwatchEvent = $stopWatch->start('runtime');

        Logging::audit()?->log('Accessing test ping page.');
        for ($i = 0; $i < 1000; $i++) {
            $response = $this->pingService->ping('http://www.phpug.hh');
        }

        $stopwatchEvent->stop();

        $content = ['runtime' => $stopwatchEvent->getDuration()];

        return $this->renderer->renderResponse($request, HttpStatus::OK, $content);
    }
}
