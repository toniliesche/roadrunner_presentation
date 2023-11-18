<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Framework\Services\Helpers;

class ArrayHelper
{
    public static function isStringHashmap(array $array): bool {
        foreach ($array as $key => $value) {
            if (!\is_string($key) || !\is_string($value)) {
                return false;
            }
        }

        return true;
    }
}
