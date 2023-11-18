<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Utility\Interfaces;

use ToniLiesche\Roadrunner\Core\Application\Utility\Exceptions\ValidationFailedException;

interface ValidationInterface
{
    /**
     * @throws ValidationFailedException
     */
    public function validate(array $data): void;
}
