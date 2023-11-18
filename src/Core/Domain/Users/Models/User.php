<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Domain\Users\Models;

use ToniLiesche\Roadrunner\Core\Application\Utility\Interfaces\ValidationRuleInterface;
use ToniLiesche\Roadrunner\Core\Application\Utility\Models\ValidationRules\ExistsRule;
use ToniLiesche\Roadrunner\Core\Application\Utility\Models\ValidationRules\IsIntRule;
use ToniLiesche\Roadrunner\Core\Application\Utility\Models\ValidationRules\IsNotEmptyRule;
use ToniLiesche\Roadrunner\Core\Application\Utility\Models\ValidationRules\IsStringRule;

readonly class User
{
    private int $id;

    private string $username;

    private string $name;

    private string $password;

    /**
     * @return ValidationRuleInterface[]
     */
    public static function getValidationRules(): array {
        return [
            new ExistsRule('id', 'username', 'name', 'password'),
            new IsNotEmptyRule('username', 'name', 'password'),
            new IsIntRule('id'),
            new IsStringRule('username', 'name', 'password'),
        ];
    }

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->username = $data['username'];
        $this->name = $data['name'];
        $this->password = $data['password'];
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
}
