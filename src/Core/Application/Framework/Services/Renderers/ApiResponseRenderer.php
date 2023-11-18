<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Framework\Services\Renderers;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpException as SlimHttpException;
use Throwable;
use ToniLiesche\Roadrunner\Core\Application\Framework\Exceptions\DataConversionException;
use ToniLiesche\Roadrunner\Core\Application\Framework\Exceptions\UnexpectedValueException;
use ToniLiesche\Roadrunner\Core\Application\Framework\Interfaces\ApiResponseRendererInterface;
use ToniLiesche\Roadrunner\Core\Application\Framework\Interfaces\ErrorResponseRendererInterface;
use ToniLiesche\Roadrunner\Core\Application\Framework\Services\Converters\ConverterProvider;
use ToniLiesche\Roadrunner\Core\Application\Framework\Services\Helpers\AcceptParser;
use ToniLiesche\Roadrunner\Core\Application\Framework\Services\RequestIdService;
use ToniLiesche\Roadrunner\Core\Domain\Shared\Enums\ErrorCode;
use ToniLiesche\Roadrunner\Infrastructure\Http\Enums\HttpPhrase;
use ToniLiesche\Roadrunner\Infrastructure\Http\Exceptions\HttpException;

readonly final class ApiResponseRenderer implements ApiResponseRendererInterface, ErrorResponseRendererInterface
{
    public function __construct(
        private RequestIdService $requestIdService,
        private ResponseFactoryInterface $responseFactory,
        private ConverterProvider $converterProvider
    ) {
    }

    /**
     * @throws DataConversionException
     * @throws UnexpectedValueException
     */
    public function renderResponse(
        ServerRequestInterface $request,
        HttpPhrase $status,
        array $payload = []
    ): ResponseInterface {
        $response = $this->responseFactory->createResponse();

        if (HttpPhrase::NO_CONTENT === $status) {
            return $response->withStatus($status->toCode());
        }

        $data = [
            'success' => true,
            'message' => \sprintf('%d %s', $status->toCode(), $status->value),
            'requestId' => $this->requestIdService->getRequestId(),
        ];

        if (!empty($payload)) {
            $data['values'] = $payload;
        }

        $converter = $this->converterProvider->getConverter(
            AcceptParser::parse($request->getHeader('Accept')[0] ?? null)
        );

        $response->getBody()->write($converter->arrayToString($data));

        return $response->withHeader('Content-Type', $converter->getContentType())->withStatus($status->toCode());
    }

    /**
     * @throws DataConversionException
     * @throws UnexpectedValueException
     */
    public function renderError(ServerRequestInterface $request, Throwable $t): ResponseInterface
    {
        $response = $this->responseFactory->createResponse();

        if ($t instanceof HttpException) {
            $code = $t->getPhrase()->toCode();
            $errorCode = $t->getErrorCode();
        } elseif ($t instanceof SlimHttpException) {
            $code = $t->getCode();
        }else {
            $code = HttpPhrase::INTERNAL_SERVER_ERROR->toCode();
        }

        $errorData = $this->prepareErrorData($t, $code, $errorCode ?? null);
        $converter = $this->converterProvider->getConverter(
            AcceptParser::parse($request->getHeader('Accept')[0] ?? null)
        );

        $response->getBody()->write($converter->arrayToString($errorData));

        return $response->withHeader('Content-Type', $converter->getContentType())->withStatus($code);
    }

    /**
     * @throws UnexpectedValueException
     */
    private function prepareErrorData(Throwable $t, int $code, ?ErrorCode $errorCode): array
    {
        $errorData = [
            'success' => false,
            'message' => \sprintf('%d %s', $code, HttpPhrase::fromCode($code)->value),
            'advancedMessage' => ($t instanceof SlimHttpException) ? $t->getDescription() : $t->getMessage(),
            'requestId' => $this->requestIdService->getRequestId(),
        ];

        if (isset($errorCode)) {
            $errorData['errorCode'] = $errorCode->name;
        }

        return $errorData;
    }
}
