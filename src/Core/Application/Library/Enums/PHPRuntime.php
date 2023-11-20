<?php

namespace ToniLiesche\Roadrunner\Core\Application\Library\Enums;

use ToniLiesche\Roadrunner\Core\Application\Config\Exceptions\InvalidConfigValueException;

enum PHPRuntime
{
    case PHP_CLI;

    case PHP_FPM;

    case ROADRUNNER;

    /**
     * @throws InvalidConfigValueException
     */
    public static function parse(string $name): PHPRuntime {
        return match($name) {
            'php-cli' => self::PHP_CLI,
            'php-fpm' => self::PHP_FPM,
            'roadrunner' => self::ROADRUNNER,
            default => throw new InvalidConfigValueException(\sprintf('Unsupported php runtime "%s" specified', $name)),
        };
    }
}
