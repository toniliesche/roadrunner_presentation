<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Infrastructure\Http\Exceptions;

use Throwable;
use ToniLiesche\Roadrunner\Core\Application\Framework\Exceptions\BaseException;
use ToniLiesche\Roadrunner\Core\Domain\Shared\Enums\ErrorCode;
use ToniLiesche\Roadrunner\Infrastructure\Http\Enums\HttpPhrase;

abstract class HttpException extends BaseException
{
    public function __construct(string $message = "", private HttpPhrase $phrase = HttpPhrase::INTERNAL_SERVER_ERROR, ?Throwable $previous = null, ?ErrorCode $errorCode = null)
    {
        parent::__construct($message, $this->phrase->toCode(), $previous, $errorCode);
    }

    public function getPhrase(): HttpPhrase
    {
        return $this->phrase;
    }
}
