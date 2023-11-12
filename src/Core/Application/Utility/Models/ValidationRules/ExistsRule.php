<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Utility\Models\ValidationRules;

use ToniLiesche\Roadrunner\Core\Application\Utility\Interfaces\ValidationInterface;
use ToniLiesche\Roadrunner\Core\Application\Utility\Models\Exceptions\ValidationFailedException;

readonly final class ExistsRule implements ValidationInterface
{
    /** @var string[] */
    private array $fields;

    public function __construct(string...$fields)
    {
        $this->fields = $fields;
    }

    public function validate(array $data): void
    {
        foreach ($this->fields as $field) {
            if (!isset($data[$field])) {
                throw new ValidationFailedException(\sprintf('Mandatory field "%s" not found.', $field));
            }
        }
    }
}