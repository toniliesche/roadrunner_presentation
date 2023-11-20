<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Utility\Validation\Traits;

use ToniLiesche\Roadrunner\Core\Application\Utility\Validation\Exceptions\ValidationFailedException;
use ToniLiesche\Roadrunner\Core\Application\Utility\Validation\Interfaces\ValidationRuleInterface;

trait DataValidator
{
    /**
     * @param ValidationRuleInterface[] $rules
     * @param array<string,mixed> $data
     *
     * @throws ValidationFailedException
     */
    private static function validateRules(array $rules, array $data): void
    {
        foreach ($rules as $rule) {
            $rule->validate($data);
        }
    }
}
