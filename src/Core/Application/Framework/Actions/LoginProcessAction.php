<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Framework\Actions;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ToniLiesche\Roadrunner\Core\Application\Utility\Exceptions\ValidationFailedException;
use ToniLiesche\Roadrunner\Core\Domain\Login\Services\LoginPayloadMapper;
use ToniLiesche\Roadrunner\Core\Domain\Shared\Enums\ErrorCode;
use ToniLiesche\Roadrunner\Core\Domain\Users\Interfaces\UserServiceInterface;
use ToniLiesche\Roadrunner\Infrastructure\Http\Enums\HttpPhrase;
use ToniLiesche\Roadrunner\Infrastructure\Http\Exceptions\BadRequestException;
use ToniLiesche\Roadrunner\Infrastructure\Http\Exceptions\UnauthorizedException;

readonly final class LoginProcessAction
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private UserServiceInterface $userService
    ) {
    }

    /**
     * @throws BadRequestException
     * @throws UnauthorizedException
     */
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $body = $request->getParsedBody();
        try {
            $this->userService->validateLogin(LoginPayloadMapper::arrayToObject($body));
        } catch(ValidationFailedException $ex) {
            throw new BadRequestException($ex->getMessage(), $ex, ErrorCode::E_INVALID_FORM_DATA_SUBMITTED);
        } catch (\Throwable $t) {
            throw new UnauthorizedException('Login failed.', $t, ErrorCode::E_LOGIN_FAILED);
        }

        return $this->responseFactory->createResponse()->withStatus(
            HttpPhrase::TEMPORARY_REDIRECT->toCode(),
            'Login Successful'
        )->withHeader('Location', '/login/success');
    }
}
