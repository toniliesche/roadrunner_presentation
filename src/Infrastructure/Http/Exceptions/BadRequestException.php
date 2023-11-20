<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Infrastructure\Http\Exceptions;

use Throwable;
use ToniLiesche\Roadrunner\Core\Application\Library\Enums\ErrorCode;
use ToniLiesche\Roadrunner\Infrastructure\Http\Enums\HttpStatus;

class BadRequestException extends HttpException
{
    public function __construct(string $message = "", ?Throwable $previous = null, ?ErrorCode $errorCode = null, bool $apiResult = false)
    {
        parent::__construct($message, HttpStatus::BAD_REQUEST, $previous, $errorCode, $apiResult);
    }
}
