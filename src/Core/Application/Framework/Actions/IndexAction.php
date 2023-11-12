<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Framework\Actions;

use JsonException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Stopwatch\Stopwatch;
use ToniLiesche\Roadrunner\Core\Domain\Ping\Interfaces\PingServiceInterface;
use ToniLiesche\Roadrunner\Infrastructure\Log\Interfaces\AuditLoggerInterface;

use function json_encode;

readonly final class IndexAction
{
    public function __construct(private AuditLoggerInterface $logService, private PingServiceInterface $pingService)
    {
    }

    /**
     * @throws JsonException
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $stopWatch = new Stopwatch();
        $stopwatchEvent = $stopWatch->start('runtime');

        $this->logService->log('Accessing index page.');
        for ($i = 0; $i < 1; $i++) {
            $this->pingService->ping('http://nginx');
        }

        $stopwatchEvent->stop();

        $content = json_encode(['runtime' => $stopwatchEvent->getDuration()], \JSON_THROW_ON_ERROR);

        $response->getBody()->write($content);

        return $response;
    }
}
