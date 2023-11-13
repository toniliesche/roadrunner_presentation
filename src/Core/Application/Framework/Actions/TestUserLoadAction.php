<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Framework\Actions;

use JsonException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Stopwatch\Stopwatch;
use Throwable;
use ToniLiesche\Roadrunner\Core\Application\Framework\Traits\RequestParserAwareTrait;
use ToniLiesche\Roadrunner\Core\Domain\Test\Interfaces\UserServiceInterface;
use ToniLiesche\Roadrunner\Infrastructure\Log\Logging;

use function json_encode;

final class TestUserLoadAction
{
    use RequestParserAwareTrait;

    public function __construct(private readonly UserServiceInterface $userService)
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

        $userId = $this->getRequestParser()->getNumericQueryParam($request, 'userId');
        Logging::audit()?->log('Accessing test user page.', ['userId' => $userId]);
        for ($i = 0; $i < 1000; $i++) {
            $response = $this->userService->getUser('http://nginx', $userId);
        }

        $stopwatchEvent->stop();

        $content = json_encode(['runtime' => $stopwatchEvent->getDuration()], \JSON_THROW_ON_ERROR);

        $response->getBody()->write($content);

        return $response;
    }
}
