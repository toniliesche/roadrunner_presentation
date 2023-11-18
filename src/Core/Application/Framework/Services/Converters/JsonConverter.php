<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Framework\Services\Converters;

use JsonException;
use ToniLiesche\Roadrunner\Core\Application\Framework\Exceptions\DataConversionException;
use ToniLiesche\Roadrunner\Core\Application\Framework\Interfaces\DataConverterInterface;

class JsonConverter implements DataConverterInterface
{
    public function arrayToString(array $data): string
    {
        try {
            return \json_encode($data, \JSON_THROW_ON_ERROR);
        } catch (JsonException $ex) {
            throw new DataConversionException('Failed converting php array to json string.', $ex->getCode(), $ex);
        }
    }

    public function stringToArray(string $data): array
    {
        try {
            return \json_decode($data, true, 512, \JSON_THROW_ON_ERROR);
        } catch (JsonException $ex) {
            throw new DataConversionException('Failed converting json string to php array.', $ex->getCode(), $ex);
        }
    }

    public function getContentType(): string
    {
        return "application/json";
    }
}
