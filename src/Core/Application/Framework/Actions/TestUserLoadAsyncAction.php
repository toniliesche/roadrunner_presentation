<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Framework\Actions;

use GuzzleHttp\Promise\Utils;
use JsonException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Stopwatch\Stopwatch;
use Throwable;
use ToniLiesche\Roadrunner\Core\Application\Framework\Traits\RequestParserAwareTrait;
use ToniLiesche\Roadrunner\Core\Domain\Test\Interfaces\UserServiceInterface;
use ToniLiesche\Roadrunner\Infrastructure\Log\Logging;

use function json_encode;

final class TestUserLoadAsyncAction
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
        Logging::audit()?->log('Accessing test user async page.', ['userId' => $userId]);
        for ($i = 0; $i < 500; $i++) {
            $promises = [];
            for ($j = 0; $j < 2; $j++) {
                $promises[] = $this->userService->getUserAsync('http://nginx', $userId);
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
