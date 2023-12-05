<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Config\Services;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Slim\App;
use ToniLiesche\Roadrunner\Core\Application\Config\Interfaces\RouteConfiguratorInterface;
use ToniLiesche\Roadrunner\Core\Application\Framework\Middlewares\SessionMiddleware;
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
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function configureRoutes(App $app): void
    {
        $container = $app->getContainer();
        $sessionMiddleware = $container->get(SessionMiddleware::class);

        $app->get('/', IndexAction::class);

        $app->get('/_internal/ping[/]', PingAction::class);

        $app->get('/login', LoginFormAction::class)
            ->add($sessionMiddleware);
        $app->post('/login/process', LoginProcessAction::class)
            ->add($sessionMiddleware);
        $app->get('/login/success', LoginSuccessAction::class)
            ->add($sessionMiddleware);

        $app->get('/test/ping', TestPingAction::class);
        $app->get('/test/ping-async', TestPingAsyncAction::class);
        $app->get('/test/user', TestUserLoadAction::class);
        $app->get('/test/user-async', TestUserLoadAsyncAction::class);

        $app->get('/user[/]', UserLoadAction::class);
    }
}
