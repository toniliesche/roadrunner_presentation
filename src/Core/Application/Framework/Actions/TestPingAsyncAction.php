<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Framework\Actions;

use GuzzleHttp\Promise\Utils;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Stopwatch\Stopwatch;
use Throwable;
use ToniLiesche\Roadrunner\Core\Application\Framework\Exceptions\DataConversionException;
use ToniLiesche\Roadrunner\Core\Application\Framework\Exceptions\UnexpectedValueException;
use ToniLiesche\Roadrunner\Core\Application\Framework\Services\Renderers\ApiResponseRenderer;
use ToniLiesche\Roadrunner\Core\Domain\Test\Interfaces\PingServiceInterface;
use ToniLiesche\Roadrunner\Infrastructure\Http\Enums\HttpPhrase;
use ToniLiesche\Roadrunner\Infrastructure\Log\Logging;

readonly final class TestPingAsyncAction
{
    public function __construct(private ApiResponseRenderer $renderer, private PingServiceInterface $pingService)
    {
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

        return $this->renderer->renderResponse($request, HttpPhrase::OK, $content);
    }
}
