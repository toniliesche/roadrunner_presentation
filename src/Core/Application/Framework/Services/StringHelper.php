<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Framework\Services;

class StringHelper
{
    public static function addTrailingSlash(string $string): string
    {
        return self::addTrailingChar($string, '/');
    }

    public static function addLeadingSlash(string $string): string
    {
        return self::addLeadingChar($string, '/');
    }

    public static function addLeadingDot(string $string): string
    {
        return self::addLeadingChar($string, '.');
    }

    public static function addTrailingDot(string $string): string
    {
        return self::addTrailingChar($string, '.');
    }

    public static function removeTrailingSlash(string $string): string
    {
        return self::removeTrailingChar($string, '/');
    }

    public static function removeLeadingSlash(string $string): string
    {
        return self::removeLeadingChar($string, '/');
    }

    public static function removeLeadingDot(string $string): string
    {
        return self::removeLeadingChar($string, '.');
    }

    public static function removeTrailingDot(string $string): string
    {
        return self::removeTrailingChar($string, '.');
    }

    public static function addTrailingChar(string $string, string $char): string
    {
        return (\substr($string, -1) !== $char) ? $string . $char : $string;
    }

    public static function removeTrailingChar(string $string, string $char): string
    {
        return (\substr($string, -1) === $char) ? \substr($string, 0, -1) : $string;
    }

    public static function addLeadingChar(string $string, string $char): string
    {
        return (!\str_starts_with($string, $char)) ? $char . $string : $string;
    }

    public static function removeLeadingChar(string $string, string $char): string
    {
        return (str_starts_with($string, $char)) ? \substr($string, 1) : $string;
    }


    public static function arrayToString(?array $data = []): string
    {
        $dataArray = [];

        if (\is_array($data) && !empty($data)) {
            foreach ($data as $key => $value) {
                if (\is_array($value)) {
                    continue;
                }
                $dataArray[] = \sprintf('%s: %s', $key, $value);
            }

            return \implode(', ', $dataArray);
        }

        return '';
    }
}
