<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Framework\Actions;

use GuzzleHttp\Promise\Utils;
use JsonException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Stopwatch\Stopwatch;
use Throwable;
use ToniLiesche\Roadrunner\Core\Domain\Test\Interfaces\UserServiceInterface;
use ToniLiesche\Roadrunner\Infrastructure\Log\Interfaces\AuditLoggerInterface;

use function json_encode;

readonly final class TestUserLoadAsyncAction
{
    public function __construct(private AuditLoggerInterface $logService, private UserServiceInterface $userService)
    {
    }

    /**
     * @throws JsonException
     * @throws Throwable
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $stopWatch = new Stopwatch();
        $stopwatchEvent = $stopWatch->start('runtime');

        $this->logService->log('Accessing test user async page.');
        for ($i = 0; $i < 500; $i++) {
            $promises = [];
            for ($j = 0; $j < 2; $j++) {
                $promises[] = $this->userService->getUserAsync('http://nginx', 1);
            }

            Utils::unwrap($promises);
            Utils::settle($promises)->wait();
        }

        $stopwatchEvent->stop();

        $content = json_encode(['runtime' => $stopwatchEvent->getDuration()], \JSON_THROW_ON_ERROR);

        $response->getBody()->write($content);

        return $response;
    }
}
