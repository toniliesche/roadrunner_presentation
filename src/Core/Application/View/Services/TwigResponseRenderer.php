<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\View\Services;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;
use Throwable;
use ToniLiesche\Roadrunner\Core\Application\Framework\Services\RequestIdService;
use ToniLiesche\Roadrunner\Core\Application\View\Interfaces\TemplateResponseRendererInterface;
use ToniLiesche\Roadrunner\Core\Domain\Users\Interfaces\UserServiceInterface;
use ToniLiesche\Roadrunner\Infrastructure\Http\Enums\HttpStatus;
use ToniLiesche\Roadrunner\Infrastructure\Http\Exceptions\HttpException;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

readonly final class TwigResponseRenderer implements TemplateResponseRendererInterface
{
    public function __construct(
        private Twig $twig,
        private UserServiceInterface $userService,
        private RequestIdService $requestIdService,
        private ResponseFactoryInterface $responseFactory
    ) {
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function renderTemplate(
        ServerRequestInterface $request,
        HttpStatus $status,
        string $template,
        array $data = []
    ): ResponseInterface {
        $response = $this->responseFactory->createResponse();

        $data['_global_user'] = $this->userService->getUser();

        $response->getBody()->write($this->twig->fetch(\sprintf('%s.twig', $template), $data));

        return $response->withStatus($status->toCode());
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function renderError(ServerRequestInterface $request, Throwable $t): ResponseInterface
    {
        $response = $this->responseFactory->createResponse();

        if ($t instanceof HttpException) {
            $status = $t->getStatus();
        } else {
            $status = HttpStatus::INTERNAL_SERVER_ERROR;
        }

        $data = [
            '_global_error' => $t,
            '_global_status' => $status,
        ];

        $response->getBody()->write($this->twig->fetch('shared/error.twig', $data));

        return $response->withStatus($status->toCode());
    }
}
