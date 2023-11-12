<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Infrastructure\Database\Users;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table('user')]
class UserEntity
{
    #[Column(name: 'id', type: 'integer'), Id, GeneratedValue('IDENTITY')]
    private int $id;

    #[Column(name: 'username', type: 'string')]
    private string $username;

    #[Column(name: 'name', type: 'string')]
    private string $name;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
