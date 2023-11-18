<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Framework\Actions;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ToniLiesche\Roadrunner\Infrastructure\Http\Enums\HttpPhrase;

readonly final class LoginProcessAction
{
    public function __construct(private ResponseFactoryInterface $responseFactory)
    {

    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        return $this->responseFactory->createResponse()->withStatus(HttpPhrase::TEMPORARY_REDIRECT->toCode(), 'Login Successful')->withHeader('Location', '/login/success');
    }
}
