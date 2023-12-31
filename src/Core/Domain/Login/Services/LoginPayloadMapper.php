<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Domain\Login\Services;

use ToniLiesche\Roadrunner\Core\Application\Utility\Validation\Exceptions\ValidationFailedException;
use ToniLiesche\Roadrunner\Core\Application\Utility\Validation\Traits\DataValidator;
use ToniLiesche\Roadrunner\Core\Domain\Login\Models\LoginPayload;

class LoginPayloadMapper
{
    use DataValidator;

    /**
     * @param array<string,mixed> $data
     *
     * @throws ValidationFailedException
     */
    public static function arrayToObject(array $data): LoginPayload
    {
        self::validateRules(LoginPayload::getValidationRules(), $data);

        return new LoginPayload($data);
    }
}
