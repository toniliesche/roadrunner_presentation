<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Framework\Factories;

use Psr\Container\ContainerInterface;
use ToniLiesche\Roadrunner\Core\Application\Framework\Enums\PHPRuntime;
use ToniLiesche\Roadrunner\Core\Application\Framework\Exceptions\InvalidConfigValueException;
use ToniLiesche\Roadrunner\Core\Application\Framework\Exceptions\MissingConfigValueException;
use ToniLiesche\Roadrunner\Core\Application\Framework\Models\Config;

final readonly class ApplicationConfigFactory
{
    /**
     * @throws InvalidConfigValueException
     * @throws MissingConfigValueException
     */
    public static function createConfig(): Config
    {
        $configValuesFile = \APP_BASE_PATH . '/res/config.yaml';
        $configValues = \yaml_parse_file($configValuesFile);
        if (!\defined('PHP_RUNTIME')) {
            throw new MissingConfigValueException('Missing global constant "PHP_RUNTIME"');
        }

        $configValues['system']['runtime'] = PHPRuntime::parse(\PHP_RUNTIME);

        return new Config(
            $configValues
        );
    }
}
