<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Infrastructure\Log\Services\Formatters;

use JsonException;
use Monolog\Formatter\NormalizerFormatter;
use Monolog\Level;
use Monolog\LogRecord;

class GelfFormatter extends NormalizerFormatter
{
    public function __construct(?string $dateFormat = null, private bool $prettyPrint = false)
    {
        parent::__construct($dateFormat);
    }

    public function format(LogRecord $record): string
    {
        $vars = parent::format($record);

        $gelfVars = [
            'version' => '1.1',
            'host' => $vars['context']['host'] ?? 'unknown host',
            'short_message' => $record->message,
            'timestamp' => $record->datetime->format('U.v'),
            'level' => $this->mapLevel($record->level),
        ];

        if (isset($vars['context']['host'])) {
            unset($vars['context']['host']);
        }

        try {
            foreach ($vars['context'] as $key => $value) {
                $key = '_' . \strtolower(\preg_replace('/(?<!^)[A-Z]/', '_$0', $key));
                $gelfVars[$key] = \is_array($value) ? \json_encode($value, \JSON_THROW_ON_ERROR) : $value;
            }

            foreach ($vars['extra'] ?? [] as $key => $value) {
                $key = '_' . \strtolower(\preg_replace('/(?<!^)[A-Z]/', '_$0', $key));
                $gelfVars[$key] = \is_array($value) ? \json_encode($value, \JSON_THROW_ON_ERROR) : $value;
            }

            $jsonFlags = \JSON_THROW_ON_ERROR;
            if ($this->prettyPrint) {
                $jsonFlags |= \JSON_PRETTY_PRINT;
            }

            return \json_encode($gelfVars, $jsonFlags) . \PHP_EOL;
        } catch (JsonException) {
            return 'error';
        }
    }

    private function mapLevel(Level $level): int
    {
        return match ($level) {
            Level::Emergency => 0,
            Level::Alert => 1,
            Level::Critical => 2,
            Level::Error => 3,
            Level::Warning => 4,
            Level::Notice => 5,
            Level::Info => 6,
            Level::Debug => 7,
        };
    }

}
