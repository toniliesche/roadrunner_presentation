<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\View\Factories;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Slim\Views\Twig;
use ToniLiesche\Roadrunner\Core\Application\Config\Exceptions\ComponentNotConfiguredException;
use ToniLiesche\Roadrunner\Core\Application\Config\Models\Config;
use ToniLiesche\Roadrunner\Infrastructure\FileSystem\Exceptions\FileSystemException;
use ToniLiesche\Roadrunner\Infrastructure\FileSystem\Models\Directory;
use ToniLiesche\Roadrunner\Infrastructure\FileSystem\Service\FileSystemService;
use Twig\Error\LoaderError;

class TwigFactory
{
    /**
     * @throws NotFoundExceptionInterface
     * @throws FileSystemException
     * @throws ContainerExceptionInterface
     * @throws ComponentNotConfiguredException
     * @throws LoaderError
     */
    public function __invoke(ContainerInterface $container): Twig
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
            $key = \sprintf('_global_%s', $key);
            $twig->offsetSet($key, $value);
        }

        return $twig;
    }
}
