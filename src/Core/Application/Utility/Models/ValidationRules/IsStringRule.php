<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Utility\Models\ValidationRules;

use ToniLiesche\Roadrunner\Core\Application\Utility\Exceptions\ValidationFailedException;
use ToniLiesche\Roadrunner\Core\Application\Utility\Interfaces\ValidationInterface;

readonly final class IsStringRule implements ValidationInterface
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
            if (isset($data[$field]) && !\is_string($data[$field])) {
                throw new ValidationFailedException(\sprintf('Field "%s" must be of type string.', $field));
            }
        }
    }
}
