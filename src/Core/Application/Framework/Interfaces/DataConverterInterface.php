<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Framework\Interfaces;

use ToniLiesche\Roadrunner\Core\Application\Framework\Exceptions\DataConversionException;

interface DataConverterInterface
{
    /**
     * @throws DataConversionException
     */
    public function arrayToString(array $data): string;

    /**
     * @throws DataConversionException
     */
    public function stringToArray(string $data): array;

    public function getContentType(): string;
}
