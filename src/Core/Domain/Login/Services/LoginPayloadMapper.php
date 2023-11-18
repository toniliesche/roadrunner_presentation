<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Domain\Login\Services;

use ToniLiesche\Roadrunner\Core\Application\Utility\Exceptions\ValidationFailedException;
use ToniLiesche\Roadrunner\Core\Application\Utility\Traits\DataValidator;
use ToniLiesche\Roadrunner\Core\Domain\Login\Models\LoginPayload;

class LoginPayloadMapper
{
    use DataValidator;

    /**
     * @throws ValidationFailedException
     */
    public static function arrayToObject(array $data): LoginPayload
    {
        self::validateRules(LoginPayload::getValidationRules(), $data);

        return new LoginPayload($data);
    }
}
