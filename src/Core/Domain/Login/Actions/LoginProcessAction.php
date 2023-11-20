<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Domain\Login\Actions;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;
use ToniLiesche\Roadrunner\Core\Application\Framework\Actions\AbstractFrontendAction;
use ToniLiesche\Roadrunner\Core\Application\Framework\Actions\AbstractRedirectAction;
use ToniLiesche\Roadrunner\Core\Application\Library\Enums\ErrorCode;
use ToniLiesche\Roadrunner\Core\Application\Library\Exceptions\PasswordVerificationFailedException;
use ToniLiesche\Roadrunner\Core\Application\Utility\Validation\Exceptions\ValidationFailedException;
use ToniLiesche\Roadrunner\Core\Domain\Login\Services\LoginPayloadMapper;
use ToniLiesche\Roadrunner\Core\Domain\Users\Interfaces\UserServiceInterface;
use ToniLiesche\Roadrunner\Infrastructure\Http\Enums\HttpStatus;
use ToniLiesche\Roadrunner\Infrastructure\Http\Exceptions\BadRequestException;
use ToniLiesche\Roadrunner\Infrastructure\Http\Exceptions\InternalServerErrorException;
use ToniLiesche\Roadrunner\Infrastructure\Http\Exceptions\NotFoundException;
use ToniLiesche\Roadrunner\Infrastructure\Http\Exceptions\UnauthorizedException;

readonly final class LoginProcessAction extends AbstractRedirectAction
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private UserServiceInterface $userService
    ) {
    }

    /**
     * @throws BadRequestException
     * @throws InternalServerErrorException
     * @throws UnauthorizedException
     */
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $body = $request->getParsedBody();
        try {
            $this->userService->validateLogin(LoginPayloadMapper::arrayToObject($body));
        } catch(ValidationFailedException $ex) {
            throw new BadRequestException($ex->getMessage(), $ex, ErrorCode::E_INVALID_FORM_DATA_SUBMITTED, false);
        } catch (PasswordVerificationFailedException|NotFoundException $ex) {
            throw new UnauthorizedException('Login failed.', $ex, ErrorCode::E_LOGIN_FAILED, false);
        } catch (Throwable $t) {
            throw new InternalServerErrorException('Caught error while processing login.', $t, ErrorCode::E_INTERNAL_SERVER_ERROR, false);
        }

        return $this->responseFactory->createResponse()->withStatus(
            HttpStatus::FOUND->toCode(),
            'Login Successful'
        )->withHeader('Location', '/login/success');
    }
}
