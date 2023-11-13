<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Infrastructure\Database\Service;

use Doctrine\ORM\EntityManagerInterface;
use ToniLiesche\Roadrunner\Infrastructure\Engine\Interfaces\RunBeforeRequestInterface;

readonly final class EntityManagerWrapper implements RunBeforeRequestInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function runBeforeRequest(): void
    {
        return;
        $this->entityManager->clear();
    }
}
