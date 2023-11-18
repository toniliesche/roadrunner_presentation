<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Infrastructure\Http\Exceptions;

use Throwable;
use ToniLiesche\Roadrunner\Core\Domain\Shared\Enums\ErrorCode;
use ToniLiesche\Roadrunner\Infrastructure\Http\Enums\HttpPhrase;

class BadRequestException extends HttpException
{
    public function __construct(string $message = "", ?Throwable $previous = null, ?ErrorCode $errorCode = null)
    {
        parent::__construct($message, HttpPhrase::BAD_REQUEST, $previous, $errorCode);
    }
}
