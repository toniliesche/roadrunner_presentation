<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Infrastructure\Engine\Interfaces;

interface RunBeforeRequestInterface
{
    public function runBeforeRequest(): void;
}
