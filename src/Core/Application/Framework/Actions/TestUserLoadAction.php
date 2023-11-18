<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Framework\Actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Stopwatch\Stopwatch;
use Throwable;
use ToniLiesche\Roadrunner\Core\Application\Framework\Interfaces\ApiResponseRendererInterface;
use ToniLiesche\Roadrunner\Core\Application\Framework\Traits\RequestParserAwareTrait;
use ToniLiesche\Roadrunner\Core\Domain\Test\Interfaces\UserServiceInterface;
use ToniLiesche\Roadrunner\Infrastructure\Http\Enums\HttpPhrase;
use ToniLiesche\Roadrunner\Infrastructure\Log\Logging;

use function json_encode;

final class TestUserLoadAction
{
    use RequestParserAwareTrait;

    public function __construct(private readonly ApiResponseRendererInterface $renderer, private readonly UserServiceInterface $userService)
    {
    }

    /**
     * @throws Throwable
     */
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $stopWatch = new Stopwatch();
        $stopwatchEvent = $stopWatch->start('runtime');

        $userId = $this->getRequestParser()->getNumericQueryParam($request, 'userId');
        Logging::audit()?->log('Accessing test user page.', ['userId' => $userId]);
        for ($i = 0; $i < 10; $i++) {
            $response = $this->userService->getUser('http://nginx', $userId);
        }

        $stopwatchEvent->stop();

        $content = ['runtime' => $stopwatchEvent->getDuration()];

        return $this->renderer->renderResponse($request, HttpPhrase::OK, $content);
    }
}
