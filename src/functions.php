<?php

declare(strict_types=1);

use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Slim\App;
use Spiral\RoadRunner\Http\PSR7Worker;
use Spiral\RoadRunner\Worker;
use ToniLiesche\Roadrunner\Core\Application\Framework\Configuration\ContainerConfigurator;
use ToniLiesche\Roadrunner\Core\Application\Framework\Configuration\MiddlewareConfigurator;
use ToniLiesche\Roadrunner\Core\Application\Framework\Configuration\RouteConfigurator;
use ToniLiesche\Roadrunner\Core\Application\Framework\Exceptions\ContainerBuildFailedException;
use ToniLiesche\Roadrunner\Core\Application\Framework\Exceptions\InvalidConfigValueException;
use ToniLiesche\Roadrunner\Core\Application\Framework\Exceptions\MissingConfigValueException;
use ToniLiesche\Roadrunner\Core\Application\Framework\Factories\AppFactory;
use ToniLiesche\Roadrunner\Core\Application\Framework\Factories\ApplicationConfigFactory;
use ToniLiesche\Roadrunner\Core\Application\Framework\Factories\ContainerFactory;
use ToniLiesche\Roadrunner\Core\Application\Framework\Services\RequestIdService;
use ToniLiesche\Roadrunner\Infrastructure\Engine\Services\RoadrunnerRequestCleaningService;
use ToniLiesche\Roadrunner\Infrastructure\FileSystem\Exceptions\FileSystemException;
use ToniLiesche\Roadrunner\Infrastructure\Http\Enums\HttpCode;
use ToniLiesche\Roadrunner\Infrastructure\Log\Enums\LogCategory;
use ToniLiesche\Roadrunner\Infrastructure\Log\Interfaces\ApplicationLoggerInterface;

/**
 * @throws JsonException
 */
function error_output(int $code, string $message, ?string $requestId = null): string
{
    $httpStatus = HttpCode::fromCode($code);

    $response = [
        'success' => false,
        'message' => sprintf('%d %s', $code, $httpStatus->value),
        'advancedMessage' => $message,
        'requestId' => $requestId,
    ];

    return json_encode($response, JSON_THROW_ON_ERROR);
}

/**
 * @throws JsonException
 */
function show_error(int $code, string $message, ?string $requestId = null): never {
    http_response_code($code);

    if (null === ($accept = $_SERVER['HTTP_ACCEPT'] ?? null)) {
        $accept = '*/*';
    }

    if ($accept === 'application/json') {
        echo error_output($code, $message, $requestId);
        header('Content-Type: application/json');
    }

    exit;
}

/**
 * @throws JsonException
 */
function error_500(string $message): never
{
    show_error(500, $message);
}

/**
 * @throws ContainerBuildFailedException
 * @throws FileSystemException
 * @throws InvalidConfigValueException
 * @throws JsonException
 * @throws MissingConfigValueException
 */
function bootstrap_app(string $runtime): App
{
    define('PHP_RUNTIME', $runtime);

    $autoloadFile = APP_BASE_PATH . '/vendor/autoload.php';
    if (false === file_exists($autoloadFile)) {
        error_500('autoload.php not found');
    }

    require $autoloadFile;

    $config = ApplicationConfigFactory::createConfig();

    $containerFactory = new ContainerFactory();
    $container = $containerFactory->createContainer($config, new ContainerConfigurator());

    $appFactory = new AppFactory(
        $container,
        new RouteConfigurator(),
        new MiddlewareConfigurator(),
    );

    return $appFactory->create($config);
}

function bootstrap_roadrunner(): PSR7Worker
{
    $worker = Worker::create();
    $psrFactory = new Psr17Factory();

    return new PSR7Worker($worker, $psrFactory, $psrFactory, $psrFactory);
}

/**
 * @throws JsonException
 */
function run_webapp_fpm(string $appname): void
{
    date_default_timezone_set('Europe/Berlin');

    try {
        $app = bootstrap_app("php-fpm");
    } catch (Throwable $t) {
        error_500($t->getMessage());
    }

    try {
        $app->run();
    } catch (Throwable $t) {
        error_500($t->getMessage());
    }
}

/**
 * @throws ContainerExceptionInterface
 * @throws JsonException
 * @throws NotFoundExceptionInterface
 */
function run_webapp_rr(string $appname): void
{
    try {
        $app = bootstrap_app("roadrunner");
    } catch (Throwable $t) {
        error_500($t->getMessage());
    }

    $container = $app->getContainer();
    if (null === $container) {
        error_500('something went wrong');
    }

    $logger = $container->get(ApplicationLoggerInterface::class);
    $logger->debug(LogCategory::FRAMEWORK, 'Application bootstrap complete. Start listening.');

    $requestIdService = $container->get(RequestIdService::class);
    $requestIdService->generateRequestId();
    $roadrunnerRequestCleaningService = $container->get(RoadrunnerRequestCleaningService::class);
    $roadrunnerRequestCleaningService->processOnWarmup();

    $psr7worker = bootstrap_roadrunner();
    while (true) {
        try {
            $request = $psr7worker->waitRequest();

            if (null === $request) {
                $logger->debug(LogCategory::FRAMEWORK, 'Stopping worker.');
                break;
            }

            $logger->debug(LogCategory::FRAMEWORK, 'Got new request.');
        } catch (Throwable $t) {
            $logger->error(LogCategory::FRAMEWORK, 'Failed initializing request.', ['error' => $t->getMessage()]);
            $psr7worker->respond(new Response(500, [], error_output(500, 'something went wrong')));
            $roadrunnerRequestCleaningService->processAfterRequest();
            continue;
        }

        try {
            $logger->debug(LogCategory::FRAMEWORK, 'Performing pre-request tasks.');
            $requestIdService->parseReferralId($request);
            $roadrunnerRequestCleaningService->processBeforeRequest();
        } catch (Throwable $t) {
            $logger->error(LogCategory::FRAMEWORK, 'Failed running pre-request tasks.', ['error' => $t->getMessage()]);
            continue;
        }

        try {
            $logger->debug(LogCategory::FRAMEWORK, 'Starting request processing.');
            $response = $app->handle($request);

            $psr7worker->respond($response);
            $logger->debug(LogCategory::FRAMEWORK, 'Finished request processing.');
        } catch (Throwable $t) {
            $logger->error(LogCategory::FRAMEWORK, 'Got uncaught throwable while processing request.', ['error' => $t->getMessage()]);
            $psr7worker->respond(new Response(500, [], error_output(500, 'something went wrong')));
        } finally {
            $roadrunnerRequestCleaningService->processAfterRequest();
        }
    }
}