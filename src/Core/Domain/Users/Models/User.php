<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Domain\Users\Models;

use ToniLiesche\Roadrunner\Core\Application\Utility\Exceptions\ValidationFailedException;
use ToniLiesche\Roadrunner\Core\Application\Utility\Models\ValidationRules\ExistsRule;
use ToniLiesche\Roadrunner\Core\Application\Utility\Models\ValidationRules\IsIntRule;
use ToniLiesche\Roadrunner\Core\Application\Utility\Models\ValidationRules\IsNotEmptyRule;
use ToniLiesche\Roadrunner\Core\Application\Utility\Models\ValidationRules\IsStringRule;
use ToniLiesche\Roadrunner\Core\Application\Utility\Traits\DataValidator;

readonly class User
{
    use DataValidator;

    private int $id;

    private string $username;

    private string $name;

    /**
     * @throws ValidationFailedException
     */
    public function __construct(array $data)
    {
        $rules = [
            new ExistsRule('id', 'username', 'name'),
            new IsNotEmptyRule('username', 'name'),
            new IsIntRule('id'),
            new IsStringRule('username', 'name'),
        ];

        $this->validateRules($rules, $data);

        $this->id = $data['id'];
        $this->username = $data['username'];
        $this->name = $data['name'];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
