<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Config\Services;

use Slim\App;
use ToniLiesche\Roadrunner\Core\Application\Config\Interfaces\RouteConfiguratorInterface;
use ToniLiesche\Roadrunner\Core\Domain\Login\Actions\LoginFormAction;
use ToniLiesche\Roadrunner\Core\Domain\Login\Actions\LoginProcessAction;
use ToniLiesche\Roadrunner\Core\Domain\Login\Actions\LoginSuccessAction;
use ToniLiesche\Roadrunner\Core\Domain\Shared\Actions\IndexAction;
use ToniLiesche\Roadrunner\Core\Domain\Shared\Actions\PingAction;
use ToniLiesche\Roadrunner\Core\Domain\Test\Actions\TestPingAction;
use ToniLiesche\Roadrunner\Core\Domain\Test\Actions\TestPingAsyncAction;
use ToniLiesche\Roadrunner\Core\Domain\Test\Actions\TestUserLoadAction;
use ToniLiesche\Roadrunner\Core\Domain\Test\Actions\TestUserLoadAsyncAction;
use ToniLiesche\Roadrunner\Core\Domain\Users\Actions\UserLoadAction;

final readonly class RouteConfigurator implements RouteConfiguratorInterface
{
    public function configureRoutes(App $app): void
    {
        $app->get('/', IndexAction::class);

        $app->get('/_internal/ping[/]', PingAction::class);

        $app->get('/login', LoginFormAction::class);
        $app->post('/login/process', LoginProcessAction::class);
        $app->get('/login/success', LoginSuccessAction::class);

        $app->get('/test/ping', TestPingAction::class);
        $app->get('/test/ping-async', TestPingAsyncAction::class);
        $app->get('/test/user', TestUserLoadAction::class);
        $app->get('/test/user-async', TestUserLoadAsyncAction::class);

        $app->get('/user[/]', UserLoadAction::class);
    }
}
