<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Framework\Actions;

use JsonException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Stopwatch\Stopwatch;
use Throwable;
use ToniLiesche\Roadrunner\Core\Domain\Test\Interfaces\UserServiceInterface;
use ToniLiesche\Roadrunner\Infrastructure\Log\Interfaces\AuditLoggerInterface;

use function json_encode;

readonly final class TestUserLoadAction
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

        $this->logService->log('Accessing test user page.');
        for ($i = 0; $i < 1000; $i++) {
            $response = $this->userService->getUser('http://nginx', 1);
        }

        $stopwatchEvent->stop();

        $content = json_encode(['runtime' => $stopwatchEvent->getDuration()], \JSON_THROW_ON_ERROR);

        $response->getBody()->write($content);

        return $response;
    }
}
