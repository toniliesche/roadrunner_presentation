<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Domain\Users\Models;

use ToniLiesche\Roadrunner\Core\Application\Utility\Validation\Interfaces\ValidationRuleInterface;
use ToniLiesche\Roadrunner\Core\Application\Utility\Validation\Models\ExistsRule;
use ToniLiesche\Roadrunner\Core\Application\Utility\Validation\Models\IsBoolRule;
use ToniLiesche\Roadrunner\Core\Application\Utility\Validation\Models\IsIntRule;
use ToniLiesche\Roadrunner\Core\Application\Utility\Validation\Models\IsNotEmptyRule;
use ToniLiesche\Roadrunner\Core\Application\Utility\Validation\Models\IsStringRule;

readonly class User
{
    private int $id;

    private string $username;

    private string $name;

    private string $password;

    private bool $loggedIn;

    /**
     * @return ValidationRuleInterface[]
     */
    public static function getValidationRules(): array {
        return [
            new ExistsRule('id', 'username', 'name', 'password'),
            new IsNotEmptyRule('username', 'name', 'password'),
            new IsIntRule('id'),
            new IsBoolRule('loggedIn'),
            new IsStringRule('username', 'name', 'password'),
        ];
    }

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->username = $data['username'];
        $this->name = $data['name'];
        $this->password = $data['password'];
        $this->loggedIn = $data['loggedIn'] ?? false;
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

    public function getPassword(): string
    {
        return $this->password;
    }

    public function isLoggedIn(): bool
    {
        return $this->loggedIn;
    }
}
