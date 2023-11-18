<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Framework\Factories;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Slim\Views\Twig;
use ToniLiesche\Roadrunner\Core\Application\Framework\Exceptions\ComponentNotConfiguredException;
use ToniLiesche\Roadrunner\Core\Application\Framework\Models\Config;
use ToniLiesche\Roadrunner\Core\Application\Framework\Services\Renderers\TwigResponseRenderer;
use ToniLiesche\Roadrunner\Core\Application\Framework\Services\RequestIdService;
use ToniLiesche\Roadrunner\Infrastructure\FileSystem\Exceptions\FileSystemException;
use ToniLiesche\Roadrunner\Infrastructure\FileSystem\Models\Directory;
use ToniLiesche\Roadrunner\Infrastructure\FileSystem\Service\FileSystemService;
use Twig\Error\LoaderError;
use Twig\Loader\FilesystemLoader;

class TwigResponseRendererFactory
{
    /**
     * @throws ComponentNotConfiguredException
     * @throws ContainerExceptionInterface
     * @throws FileSystemException
     * @throws LoaderError
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): TwigResponseRenderer
    {
        $config = $container->get(Config::class);
        $twigConfig = $config->getFrameworkConfig()->getTemplatingConfig();

        if ($twigConfig->isCachingEnabled()) {
            $fileSystemService = $container->get(FileSystemService::class);
            $templateCacheDirectory = new Directory(['basePath' => $twigConfig->getCachePath()]);
            if (false === $fileSystemService->checkDirectoryExists(
                    $templateCacheDirectory
                ) && false === $fileSystemService->createDirectory($templateCacheDirectory, 0755, true)) {
                throw new FileSystemException('Could not create template cache directory');
            }

            $settings = ['cache' => $twigConfig->getCachePath()];
        }

        $twig = Twig::create($twigConfig->getTemplatePath(), $settings ?? []);

        foreach ($twigConfig->getBaseVars() as $key => $value) {
            $twig->offsetSet($key, $value);
        }

        return new TwigResponseRenderer(
            $twig,
            $container->get(RequestIdService::class),
            $container->get(ResponseFactoryInterface::class)
        );
    }
}
