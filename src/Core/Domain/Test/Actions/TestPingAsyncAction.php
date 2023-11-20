<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Domain\Test\Actions;

use GuzzleHttp\Promise\Utils;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Stopwatch\Stopwatch;
use Throwable;
use ToniLiesche\Roadrunner\Core\Application\Framework\Actions\AbstractAPIAction;
use ToniLiesche\Roadrunner\Core\Application\Library\Exceptions\DataConversionException;
use ToniLiesche\Roadrunner\Core\Application\Library\Exceptions\UnexpectedValueException;
use ToniLiesche\Roadrunner\Core\Application\View\Services\ApiResponseRenderer;
use ToniLiesche\Roadrunner\Core\Domain\Test\Interfaces\PingServiceInterface;
use ToniLiesche\Roadrunner\Infrastructure\Http\Enums\HttpStatus;
use ToniLiesche\Roadrunner\Infrastructure\Log\Logging;

readonly final class TestPingAsyncAction extends AbstractAPIAction
{
    public function __construct(private PingServiceInterface $pingService, ApiResponseRenderer $renderer)
    {
        parent::__construct($renderer);
    }

    /**
     * @throws DataConversionException
     * @throws UnexpectedValueException
     * @throws Throwable
     */
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $stopWatch = new Stopwatch();
        $stopwatchEvent = $stopWatch->start('runtime');

        Logging::audit()?->log('Accessing test ping async page.');
        for ($i = 0; $i < 250; $i++) {
            $promises = [];
            for ($j = 0; $j < 4; $j++) {
                $promises[] = $this->pingService->pingAsync('http://nginx');
            }

            Utils::unwrap($promises);
            Utils::settle($promises)->wait();
        }

        $stopwatchEvent->stop();

        $content = ['runtime' => $stopwatchEvent->getDuration()];

        return $this->renderer->renderResponse($request, HttpStatus::OK, $content);
    }
}
