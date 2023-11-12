<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Framework\Configuration;

use Slim\App;
use ToniLiesche\Roadrunner\Core\Application\Framework\Actions\IndexAction;
use ToniLiesche\Roadrunner\Core\Application\Framework\Actions\PingAction;
use ToniLiesche\Roadrunner\Core\Application\Framework\Actions\UserLoadAction;
use ToniLiesche\Roadrunner\Core\Application\Framework\Interfaces\RouteConfiguratorInterface;

final readonly class RouteConfigurator implements RouteConfiguratorInterface
{
    public function configureRoutes(App $app): void
    {
        $app->get('/_internal/ping[/]', PingAction::class);
        $app->get('/', IndexAction::class);
        $app->get('/user[/]', UserLoadAction::class);
    }
}
