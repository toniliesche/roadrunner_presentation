<?php

declare(strict_types=1);

use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Slim\App;
use Spiral\RoadRunner\Http\PSR7Worker;
use Spiral\RoadRunner\Worker;
use ToniLiesche\Roadrunner\Core\Application\Config\Exceptions\InvalidConfigValueException;
use ToniLiesche\Roadrunner\Core\Application\Config\Exceptions\MissingConfigValueException;
use ToniLiesche\Roadrunner\Core\Application\Config\Factories\ApplicationConfigFactory;
use ToniLiesche\Roadrunner\Core\Application\Config\Factories\ContainerFactory;
use ToniLiesche\Roadrunner\Core\Application\Config\Services\ContainerConfigurator;
use ToniLiesche\Roadrunner\Core\Application\Config\Services\MiddlewareConfigurator;
use ToniLiesche\Roadrunner\Core\Application\Config\Services\RouteConfigurator;
use ToniLiesche\Roadrunner\Core\Application\Framework\Factories\AppFactory;
use ToniLiesche\Roadrunner\Core\Application\Framework\Services\RequestIdService;
use ToniLiesche\Roadrunner\Core\Application\Library\Exceptions\ContainerBuildFailedException;
use ToniLiesche\Roadrunner\Core\Application\Library\Exceptions\UnexpectedValueException;
use ToniLiesche\Roadrunner\Infrastructure\Engine\Services\RoadrunnerRequestCleaningService;
use ToniLiesche\Roadrunner\Infrastructure\FileSystem\Exceptions\FileSystemException;
use ToniLiesche\Roadrunner\Infrastructure\Http\Enums\HttpStatus;
use ToniLiesche\Roadrunner\Infrastructure\Log\Enums\LogCategory;
use ToniLiesche\Roadrunner\Infrastructure\Log\Logging;
use ToniLiesche\Roadrunner\Infrastructure\Tracing\Tracing;

/**
 * @throws JsonException
 * @throws UnexpectedValueException
 */
function error_output(int $code, string $message, ?string $requestId = null): string
{
    $httpStatus = HttpStatus::fromCode($code);

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
 * @throws UnexpectedValueException
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
 * @throws UnexpectedValueException
 */
function error_500(string $message): never
{
    show_error(500, $message);
}

/**
 * @throws ContainerBuildFailedException
 * @throws ContainerExceptionInterface
 * @throws FileSystemException
 * @throws InvalidConfigValueException
 * @throws JsonException
 * @throws MissingConfigValueException
 * @throws NotFoundExceptionInterface
 * @throws UnexpectedValueException
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

    Logging::init($container);
    Logging::application()?->debug(LogCategory::FRAMEWORK, 'Container created. Start application bootstrap.');
    Tracing::init($container);

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
 * @throws UnexpectedValueException
 */
function run_webapp_fpm(string $appname): void
{
    date_default_timezone_set('Europe/Berlin');

    try {
        $app = bootstrap_app("php-fpm");
    } catch (Throwable $t) {
        error_500($t->getMessage());
    }

    $container = $app->getContainer();
    if (null === $container) {
        error_500('Container could not be retrieved');
    }

    try {
        Logging::application()?->debug(LogCategory::FRAMEWORK, 'Starting request processing.');
        $app->run();
        Logging::application()?->debug(LogCategory::FRAMEWORK, 'Finished request processing.');
    } catch (Throwable $t) {
        Logging::application()?->error(LogCategory::FRAMEWORK, 'Got uncaught throwable while processing request.', ['error' => $t->getMessage()]);
        error_500($t->getMessage());
    }
}

/**
 * @throws ContainerExceptionInterface
 * @throws JsonException
 * @throws NotFoundExceptionInterface
 * @throws UnexpectedValueException
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
        error_500('Container could not be retrieved');
    }

    Tracing::start('boostrap');
    Logging::application()?->debug(LogCategory::FRAMEWORK, 'Application bootstrap complete. Start listening.');

    $requestIdService = $container->get(RequestIdService::class);
    $requestIdService->generateRequestId();
    $roadrunnerRequestCleaningService = $container->get(RoadrunnerRequestCleaningService::class);
    $roadrunnerRequestCleaningService->processOnWarmup();

    $psr7worker = bootstrap_roadrunner();

    Tracing::finish();
    while (true) {
        try {
            Logging::application()?->debug(LogCategory::FRAMEWORK, 'Waiting for new request.');
            $request = $psr7worker->waitRequest();

            if (null === $request) {
                Logging::application()?->debug(LogCategory::FRAMEWORK, 'Stopping worker.');
                break;
            }

            Tracing::start('request');
            Tracing::addEvent('Got new request.');
            Logging::application()?->debug(LogCategory::FRAMEWORK, 'Got new request.');

        } catch (Throwable $t) {
            Tracing::addEvent('Failed initializing request.', ['error' => $t->getMessage()]);
            Logging::application()?->error(LogCategory::FRAMEWORK, 'Failed initializing request.', ['error' => $t->getMessage()]);
            $psr7worker->respond(new Response(500, [], error_output(500, 'something went wrong')));
            $roadrunnerRequestCleaningService->processAfterRequest();

            Tracing::finish();
            continue;
        }

        try {
            Tracing::addEvent('Performing pre-request tasks.');
            Logging::application()?->debug(LogCategory::FRAMEWORK, 'Performing pre-request tasks.');
            $requestIdService->parseReferralId($request);
            $roadrunnerRequestCleaningService->processBeforeRequest();
        } catch (Throwable $t) {
            Tracing::addEvent('Failed running pre-request tasks.', ['error' => $t->getMessage()]);
            Logging::application()?->error(LogCategory::FRAMEWORK, 'Failed running pre-request tasks.', ['error' => $t->getMessage()]);

            Tracing::finish();
            continue;
        }

        try {
            Tracing::addEvent('Starting request processing.');
            Logging::application()?->debug(LogCategory::FRAMEWORK, 'Starting request processing.');
            $response = $app->handle($request);

            $psr7worker->respond($response);
            Tracing::addEvent('Finished request processing.');
            Logging::application()?->debug(LogCategory::FRAMEWORK, 'Finished request processing.');
        } catch (Throwable $t) {
            Tracing::addEvent('Got uncaught throwable while processing request.', ['error' => $t->getMessage()]);
            Logging::application()?->error(LogCategory::FRAMEWORK, 'Got uncaught throwable while processing request.', ['error' => $t->getMessage()]);
            $psr7worker->respond(new Response(500, [], error_output(500, 'something went wrong')));

        } finally {
            $roadrunnerRequestCleaningService->processAfterRequest();
            Tracing::finish();
        }
    }
}