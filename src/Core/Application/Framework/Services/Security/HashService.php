<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Framework\Services\Security;

use ToniLiesche\Roadrunner\Core\Application\Framework\Exceptions\PasswordVerificationFailedException;

class HashService
{
    public static function hashPassword(string $password): string
    {
        return \password_hash($password, \PASSWORD_DEFAULT);
    }

    /**
     * @throws PasswordVerificationFailedException
     */
    public static function validatePassword(string $password, string $hash): void
    {
        if (true !== \password_verify($password, $hash)) {
            throw new PasswordVerificationFailedException('Password did not match hash sum value');
        }
    }
}
