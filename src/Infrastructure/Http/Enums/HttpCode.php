<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Infrastructure\Http\Enums;

enum HttpCode: string
{
    case INTERNAL_SERVER_ERROR = 'Internal Server Error';
    case NOT_FOUND = 'Not Found';

    public static function fromCode(int $code): HttpCode {
        return match($code) {
            404 => self::NOT_FOUND,
            default => self::INTERNAL_SERVER_ERROR,
        };
    }
}
