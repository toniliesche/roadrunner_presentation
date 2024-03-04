<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Config\Factories;

use ToniLiesche\Roadrunner\Core\Application\Config\Exceptions\InvalidConfigValueException;
use ToniLiesche\Roadrunner\Core\Application\Config\Exceptions\MissingConfigValueException;
use ToniLiesche\Roadrunner\Core\Application\Config\Models\Config;
use ToniLiesche\Roadrunner\Core\Application\Library\Enums\PHPRuntime;

readonly final class ApplicationConfigFactory
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
