<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Framework\Actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ToniLiesche\Roadrunner\Core\Application\Framework\Interfaces\ApiResponseRendererInterface;
use ToniLiesche\Roadrunner\Infrastructure\Http\Enums\HttpPhrase;

readonly final class IndexAction
{
    public function __construct(private ApiResponseRendererInterface $renderer)
    {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        return $this->renderer->renderResponse($request, HttpPhrase::OK);
    }
}
