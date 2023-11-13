<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Framework\Configuration;

use Slim\App;
use ToniLiesche\Roadrunner\Core\Application\Framework\Actions\IndexAction;
use ToniLiesche\Roadrunner\Core\Application\Framework\Actions\PingAction;
use ToniLiesche\Roadrunner\Core\Application\Framework\Actions\TestPingAction;
use ToniLiesche\Roadrunner\Core\Application\Framework\Actions\TestPingAsyncAction;
use ToniLiesche\Roadrunner\Core\Application\Framework\Actions\TestUserLoadAction;
use ToniLiesche\Roadrunner\Core\Application\Framework\Actions\TestUserLoadAsyncAction;
use ToniLiesche\Roadrunner\Core\Application\Framework\Actions\UserLoadAction;
use ToniLiesche\Roadrunner\Core\Application\Framework\Interfaces\RouteConfiguratorInterface;

final readonly class RouteConfigurator implements RouteConfiguratorInterface
{
    public function configureRoutes(App $app): void
    {
        $app->get('/', IndexAction::class);
        $app->get('/test/ping', TestPingAction::class);
        $app->get('/test/ping-async', TestPingAsyncAction::class);
        $app->get('/test/user', TestUserLoadAction::class);
        $app->get('/test/user-async', TestUserLoadAsyncAction::class);
        $app->get('/_internal/ping[/]', PingAction::class);
        $app->get('/user[/]', UserLoadAction::class);
    }
}
