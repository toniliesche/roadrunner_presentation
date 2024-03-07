<?php

namespace ToniLiesche\Roadrunner\Core\Application\Library\Enums;

enum LogType
{
    case DEV;

    case GRAYLOG;

    case LOCAL;

    case OTEL;

    case ROADRUNNER;

    public static function fromString(string $type): LogType
    {
        return match (\strtolower($type)) {
            'dev' => self::DEV,
            'graylog' => self::GRAYLOG,
            'otel' => self::OTEL,
            'rr' => self::ROADRUNNER,
            default => self::LOCAL,
        };
    }
}
