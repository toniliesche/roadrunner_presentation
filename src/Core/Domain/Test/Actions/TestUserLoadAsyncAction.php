<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Domain\Test\Actions;

use GuzzleHttp\Promise\Utils;
use JsonException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Stopwatch\Stopwatch;
use Throwable;
use ToniLiesche\Roadrunner\Core\Application\Framework\Actions\AbstractAPIAction;
use ToniLiesche\Roadrunner\Core\Application\View\Interfaces\ApiResponseRendererInterface;
use ToniLiesche\Roadrunner\Core\Domain\Test\Interfaces\UserServiceInterface;
use ToniLiesche\Roadrunner\Infrastructure\Http\Enums\HttpStatus;
use ToniLiesche\Roadrunner\Infrastructure\Http\Traits\RequestParserAwareTrait;
use ToniLiesche\Roadrunner\Infrastructure\Log\Logging;

readonly final class TestUserLoadAsyncAction extends AbstractAPIAction
{
    use RequestParserAwareTrait;

    public function __construct(private UserServiceInterface $userService, ApiResponseRendererInterface $renderer)
    {
        parent::__construct($renderer);
    }

    /**
     * @throws JsonException
     * @throws Throwable
     */
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $stopWatch = new Stopwatch();
        $stopwatchEvent = $stopWatch->start('runtime');

        $userId = $this->getRequestParser()->getNumericQueryParam($request, 'userId');
        Logging::audit()?->log('Accessing test user async page.', ['userId' => $userId]);
        for ($i = 0; $i < 10; $i++) {
            $promises = [];
            for ($j = 0; $j < 2; $j++) {
                $promises[] = $this->userService->getUserAsync('http://nginx', $userId);
            }

            Utils::unwrap($promises);
            Utils::settle($promises)->wait();
        }

        $stopwatchEvent->stop();

        $content = ['runtime' => $stopwatchEvent->getDuration()];

        return $this->renderer->renderResponse($request, HttpStatus::OK, $content);
    }
}
