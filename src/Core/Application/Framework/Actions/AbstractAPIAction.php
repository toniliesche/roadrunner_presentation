<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Framework\Actions;

use ToniLiesche\Roadrunner\Core\Application\View\Services\ApiResponseRenderer;

readonly abstract class AbstractAPIAction
{
    public function __construct(protected ApiResponseRenderer $renderer)
    {
    }
}
