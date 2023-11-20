<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Utility\Validation\Interfaces;

use ToniLiesche\Roadrunner\Core\Application\Utility\Validation\Exceptions\ValidationFailedException;

interface ValidationRuleInterface
{
    /**
     * @param array<string,mixed> $data
     *
     * @throws ValidationFailedException
     */
    public function validate(array $data): void;
}
