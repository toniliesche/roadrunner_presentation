<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Utility\Validation\Models;

use ToniLiesche\Roadrunner\Core\Application\Utility\Validation\Exceptions\ValidationFailedException;
use ToniLiesche\Roadrunner\Core\Application\Utility\Validation\Interfaces\ValidationRuleInterface;

readonly final class IsIntRule implements ValidationRuleInterface
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
            if (isset($data[$field]) && !\is_int($data[$field])) {
                throw new ValidationFailedException(\sprintf('Field "%s" must be of type integer.', $field));
            }
        }
    }
}
