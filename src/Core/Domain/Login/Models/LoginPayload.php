<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Domain\Login\Models;

use ToniLiesche\Roadrunner\Core\Application\Utility\Interfaces\ValidationRuleInterface;
use ToniLiesche\Roadrunner\Core\Application\Utility\Models\ValidationRules\ExistsRule;
use ToniLiesche\Roadrunner\Core\Application\Utility\Models\ValidationRules\IsIntRule;
use ToniLiesche\Roadrunner\Core\Application\Utility\Models\ValidationRules\IsNotEmptyRule;
use ToniLiesche\Roadrunner\Core\Application\Utility\Models\ValidationRules\IsStringRule;

class LoginPayload
{
    private string $username;

    private string $password;

    /**
     * @return ValidationRuleInterface[]
     */
    public static function getValidationRules(): array {
        return [
            new ExistsRule('username', 'password'),
            new IsNotEmptyRule('username', 'password'),
            new IsStringRule('username', 'password'),
        ];
    }

    public function __construct(array $data = [])
    {
        if (isset($data['username'])) {
            $this->username = $data['username'];
        }

        if (isset($data['password'])) {
            $this->password = $data['password'];
        }
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
}
