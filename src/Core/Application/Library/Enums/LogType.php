<?php

namespace ToniLiesche\Roadrunner\Core\Application\Library\Enums;

enum LogType
{
    case DEV;

    case GRAYLOG;

    case LOCAL;

    public static function fromString(string $type): LogType
    {
        return match (\strtolower($type)) {
            'dev' => self::DEV,
            'graylog' => self::GRAYLOG,
            default => self::LOCAL,
        };
    }
}
