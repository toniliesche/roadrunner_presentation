<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Framework\Configuration;

use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseFactoryInterface;
use ToniLiesche\Roadrunner\Core\Application\Framework\Actions\IndexAction;
use ToniLiesche\Roadrunner\Core\Application\Framework\Actions\LoginFormAction;
use ToniLiesche\Roadrunner\Core\Application\Framework\Actions\LoginProcessAction;
use ToniLiesche\Roadrunner\Core\Application\Framework\Actions\PingAction;
use ToniLiesche\Roadrunner\Core\Application\Framework\Actions\TestPingAction;
use ToniLiesche\Roadrunner\Core\Application\Framework\Actions\TestPingAsyncAction;
use ToniLiesche\Roadrunner\Core\Application\Framework\Factories\RequestIdServiceFactory;
use ToniLiesche\Roadrunner\Core\Application\Framework\Factories\TwigResponseRendererFactory;
use ToniLiesche\Roadrunner\Core\Application\Framework\Interfaces\ApiResponseRendererInterface;
use ToniLiesche\Roadrunner\Core\Application\Framework\Interfaces\ContainerConfiguratorInterface;
use ToniLiesche\Roadrunner\Core\Application\Framework\Interfaces\ErrorResponseRendererInterface;
use ToniLiesche\Roadrunner\Core\Application\Framework\Interfaces\TemplateResponseRendererInterface;
use ToniLiesche\Roadrunner\Core\Application\Framework\Interfaces\UuidServiceInterface;
use ToniLiesche\Roadrunner\Core\Application\Framework\Services\Renderers\ApiResponseRenderer;
use ToniLiesche\Roadrunner\Core\Application\Framework\Services\Renderers\TwigResponseRenderer;
use ToniLiesche\Roadrunner\Core\Application\Framework\Services\RequestIdService;
use ToniLiesche\Roadrunner\Core\Application\Framework\Services\UuidService;
use ToniLiesche\Roadrunner\Core\Domain\Test\Factories\PingServiceFactory;
use ToniLiesche\Roadrunner\Core\Domain\Test\Factories\UserServiceFactory;
use ToniLiesche\Roadrunner\Core\Domain\Test\Interfaces\PingServiceInterface;
use ToniLiesche\Roadrunner\Core\Domain\Test\Interfaces\UserServiceInterface as UserServiceInterfaceTest;
use ToniLiesche\Roadrunner\Core\Domain\Test\Services\PingService;
use ToniLiesche\Roadrunner\Core\Domain\Test\Services\UserService as UserServiceTest;
use ToniLiesche\Roadrunner\Core\Domain\Users\Interfaces\UserDataProviderInterface;
use ToniLiesche\Roadrunner\Core\Domain\Users\Interfaces\UserServiceInterface;
use ToniLiesche\Roadrunner\Core\Domain\Users\Services\UserService;
use ToniLiesche\Roadrunner\Infrastructure\Database\Factories\EntityManagerFactory;
use ToniLiesche\Roadrunner\Infrastructure\Database\Users\UserDataProvider;
use ToniLiesche\Roadrunner\Infrastructure\Database\Users\UserRepository;
use ToniLiesche\Roadrunner\Infrastructure\Engine\Services\RoadrunnerRequestCleaningService;
use ToniLiesche\Roadrunner\Infrastructure\Http\Factories\ClientFactory;
use ToniLiesche\Roadrunner\Infrastructure\Log\Factories\ApplicationLoggerFactory;
use ToniLiesche\Roadrunner\Infrastructure\Log\Factories\AuditLoggerFactory;
use ToniLiesche\Roadrunner\Infrastructure\Log\Factories\LogEntryContextProviderFactory;
use ToniLiesche\Roadrunner\Infrastructure\Log\Factories\SqlLoggerFactory;
use ToniLiesche\Roadrunner\Infrastructure\Log\Interfaces\ApplicationLoggerInterface;
use ToniLiesche\Roadrunner\Infrastructure\Log\Interfaces\AuditLoggerInterface;
use ToniLiesche\Roadrunner\Infrastructure\Log\Interfaces\SqlLoggerInterface;
use ToniLiesche\Roadrunner\Infrastructure\Log\Services\ApplicationLogger;
use ToniLiesche\Roadrunner\Infrastructure\Log\Services\AuditLogger;
use ToniLiesche\Roadrunner\Infrastructure\Log\Services\LogEntryContextProvider;
use ToniLiesche\Roadrunner\Infrastructure\Log\Services\SqlLogger;

use function DI\autowire;
use function DI\create;
use function DI\factory;
use function DI\get;

final readonly class ContainerConfigurator implements ContainerConfiguratorInterface
{
    public function getDefinitions(): array
    {
        return [
            ApiResponseRendererInterface::class => get(ApiResponseRenderer::class),
            ApiResponseRenderer::class => autowire(),

            ApplicationLoggerInterface::class => get(ApplicationLogger::class),
            ApplicationLogger::class => factory(ApplicationLoggerFactory::class),
            ApplicationLoggerFactory::class => create(),

            AuditLoggerInterface::class => get(AuditLogger::class),
            AuditLogger::class => factory(AuditLoggerFactory::class),
            AuditLoggerFactory::class => autowire(),

            Client::class => factory(ClientFactory::class),
            ClientFactory::class => create(),

            EntityManagerInterface::class => factory(EntityManagerFactory::class),
            EntityManagerFactory::class => create(),

            ErrorResponseRendererInterface::class => get(ApiResponseRenderer::class),

            IndexAction::class => autowire(),

            LogEntryContextProvider::class => factory(LogEntryContextProviderFactory::class),
            LogEntryContextProviderFactory::class => create(),

            LoginFormAction::class => autowire(),
            LoginProcessAction::class => autowire(),

            PingAction::class => autowire(),
            PingServiceInterface::class => get(PingService::class),
            PingService::class => factory(PingServiceFactory::class),
            PingServiceFactory::class => create(),

            Psr17Factory::class => create(),

            RequestIdService::class => factory(RequestIdServiceFactory::class),
            RequestIdServiceFactory::class => create(),

            ResponseFactoryInterface::class => get(Psr17Factory::class),

            RoadrunnerRequestCleaningService::class => create(),

            SqlLoggerInterface::class => get(SqlLogger::class),
            SqlLogger::class => factory(SqlLoggerFactory::class),
            SqlLoggerFactory::class => create(),

            TemplateResponseRendererInterface::class => get(TwigResponseRenderer::class),

            TestPingAction::class => autowire(),
            TestPingAsyncAction::class => autowire(),

            TwigResponseRenderer::class => factory(TwigResponseRendererFactory::class),
            TwigResponseRendererFactory::class => create(),

            UserDataProviderInterface::class => get(UserDataProvider::class),
            UserDataProvider::class => autowire(),

            UserRepository::class => autowire(),

            UserServiceInterfaceTest::class => get(UserServiceTest::class),
            UserServiceTest::class => factory(UserServiceFactory::class),
            UserServiceFactory::class => create(),

            UserServiceInterface::class => get(UserService::class),
            UserService::class => autowire(),

            UuidServiceInterface::class => get(UuidService::class),
            UuidService::class => create(),
        ];
    }
}
