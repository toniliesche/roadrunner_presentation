<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Framework\Interfaces;

interface ContainerConfiguratorInterface
{
    public function getDefinitions(): array;
}
