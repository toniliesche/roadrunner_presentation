<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Framework\Actions;

use ToniLiesche\Roadrunner\Core\Application\View\Interfaces\TemplateResponseRendererInterface;

readonly abstract class AbstractFrontendAction
{
    public function __construct(protected TemplateResponseRendererInterface $renderer)
    {

    }
}
