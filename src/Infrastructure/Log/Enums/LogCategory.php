<?php

namespace ToniLiesche\Roadrunner\Infrastructure\Log\Enums;

enum LogCategory: string
{
    case DATABASE = 'database';
    case FRAMEWORK = 'framework';
    case SYSTEM = 'system';
}
