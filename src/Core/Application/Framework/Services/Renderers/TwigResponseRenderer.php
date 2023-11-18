<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Framework\Services\Renderers;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;
use ToniLiesche\Roadrunner\Core\Application\Framework\Interfaces\TemplateResponseRendererInterface;
use ToniLiesche\Roadrunner\Core\Application\Framework\Services\RequestIdService;
use ToniLiesche\Roadrunner\Infrastructure\Http\Enums\HttpPhrase;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

readonly final class TwigResponseRenderer implements TemplateResponseRendererInterface
{
    public function __construct(
        private Twig $twig,
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
        HttpPhrase $status,
        string $template,
        array $data = []
    ): ResponseInterface {
        $response = $this->responseFactory->createResponse();

        $response->getBody()->write($this->twig->fetch(\sprintf('%s.twig', $template), $data));

        return $response->withStatus($status->toCode());
    }
}
