<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Utility\Traits;

use ToniLiesche\Roadrunner\Core\Application\Utility\Exceptions\ValidationFailedException;
use ToniLiesche\Roadrunner\Core\Application\Utility\Interfaces\ValidationInterface;

trait DataValidator
{
    /**
     * @param ValidationInterface[] $rules
     *
     * @throws ValidationFailedException
     */
    private function validateRules(array $rules, array $data): void
    {
        foreach ($rules as $rule) {
            $rule->validate($data);
        }
    }
}
