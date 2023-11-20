<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Infrastructure\Http\Exceptions;

use Throwable;
use ToniLiesche\Roadrunner\Core\Application\Library\Enums\ErrorCode;
use ToniLiesche\Roadrunner\Infrastructure\Http\Enums\HttpStatus;

final class UnauthorizedException extends HttpException
{
    public function __construct(string $message = "", ?Throwable $previous = null, ?ErrorCode $errorCode = null, bool $apiResult = true)
    {
        parent::__construct($message, HttpStatus::UNAUTHORIZED, $previous, $errorCode, $apiResult);
    }
}
