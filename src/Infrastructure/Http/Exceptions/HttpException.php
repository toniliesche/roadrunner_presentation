<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Infrastructure\Http\Exceptions;

use Throwable;
use ToniLiesche\Roadrunner\Core\Application\Library\Enums\ErrorCode;
use ToniLiesche\Roadrunner\Core\Application\Library\Exceptions\BaseException;
use ToniLiesche\Roadrunner\Infrastructure\Http\Enums\HttpStatus;

abstract class HttpException extends BaseException
{
    public function __construct(
        string $message = "",
        private readonly HttpStatus $status = HttpStatus::INTERNAL_SERVER_ERROR,
        ?Throwable $previous = null,
        ?ErrorCode $errorCode = null,
        private readonly bool $apiResult = false
    ) {
        parent::__construct($message, $this->status->toCode(), $previous, $errorCode);
    }

    public function getStatus(): HttpStatus
    {
        return $this->status;
    }

    public function getApiResult(): bool
    {
        return $this->apiResult;
    }
}
