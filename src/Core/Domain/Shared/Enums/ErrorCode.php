<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Domain\Shared\Enums;

enum ErrorCode: string
{
    case E_INVALID_FORM_DATA_SUBMITTED = 'Invalid form data submitted.';
    case E_LOGIN_FAILED = 'Login failed.';
    case E_PAGE_NOT_FOUND = 'Page not found';
    case E_USER_NOT_FOUND = 'User not found';
}
