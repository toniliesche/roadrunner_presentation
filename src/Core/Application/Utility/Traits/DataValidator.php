<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Utility\Traits;

use ToniLiesche\Roadrunner\Core\Application\Utility\Exceptions\ValidationFailedException;
use ToniLiesche\Roadrunner\Core\Application\Utility\Interfaces\ValidationRuleInterface;

trait DataValidator
{
    /**
     * @param ValidationRuleInterface[] $rules
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
