<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Library\DataFormats\Services;

use ToniLiesche\Roadrunner\Core\Application\Library\DataFormats\Interfaces\DataConverterInterface;
use ToniLiesche\Roadrunner\Core\Application\Library\Exceptions\UnexpectedValueException;

final class ConverterProvider
{
    /** @var array<string,DataConverterInterface> */
    private array $converters = [];

    /**
     * @throws UnexpectedValueException
     */
    public function getConverter(string $format): DataConverterInterface
    {
        if (!isset($this->converters[$format])) {
            $converter = $this->resolveConverter($format);
            $this->converters[$format] = new $converter();
        }

        return $this->converters[$format];
    }

    /**
     * @throws UnexpectedValueException
     */
    private function resolveConverter(string $format): DataConverterInterface
    {
        return match($format) {
            'json' => new JsonConverter(),
            default => throw new UnexpectedValueException(\sprintf('Cannot create converter for specified data format "%s".', $format))
        };
    }
}
