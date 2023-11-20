<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Infrastructure\Http\Services;

class AcceptParser
{
    public static function parse(?string $accept): string
    {
        return match($accept) {
            default => 'json',
        };
    }
}
