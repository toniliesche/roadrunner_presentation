<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Framework\Exceptions;

use Exception;
use Throwable;
use ToniLiesche\Roadrunner\Core\Domain\Shared\Enums\ErrorCode;

abstract class BaseException extends Exception
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null, private ?ErrorCode $errorCode = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function getErrorCode(): ?ErrorCode
    {
        return $this->errorCode ?? null;
    }
}
