<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Config\Services;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Slim\App;
use ToniLiesche\Roadrunner\Core\Application\Config\Interfaces\RouteConfiguratorInterface;
use ToniLiesche\Roadrunner\Core\Application\Framework\Middlewares\SessionMiddleware;
use ToniLiesche\Roadrunner\Core\Application\Library\Exceptions\PreconditionFailedException;
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

readonly final class RouteConfigurator implements RouteConfiguratorInterface
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws PreconditionFailedException
     */
    public function configureRoutes(App $app): void
    {
        $container = $app->getContainer();

        if (!isset($container)) {
            throw new PreconditionFailedException('No container available to resolve middlewares from.');
        }

        $sessionMiddleware = $container->get(SessionMiddleware::class);

        $app->get('/', IndexAction::class)->setName('index');

        $app->get('/_internal/ping[/]', PingAction::class)->setName('ping');

        $app->get('/login', LoginFormAction::class)
            ->add($sessionMiddleware)->setName('login-form');
        $app->post('/login/process', LoginProcessAction::class)
            ->add($sessionMiddleware)->setName('login-submit');
        $app->get('/login/success', LoginSuccessAction::class)
            ->add($sessionMiddleware)->setName('login-result');

        $app->get('/test/ping', TestPingAction::class)->setName('test-ping');
        $app->get('/test/ping-async', TestPingAsyncAction::class)->setName('test-ping-async');
        $app->get('/test/user', TestUserLoadAction::class)->setName('test-load-user');
        $app->get('/test/user-async', TestUserLoadAsyncAction::class)->setName('test-load-user-async');

        $app->get('/user[/]', UserLoadAction::class)->setName('load-user');
    }
}
