<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Framework\Services;

use Psr\Http\Message\ServerRequestInterface;

class RequestParser
{
    public function getNumericQueryParam(ServerRequestInterface $request, string $parameterName): int
    {
        $queryParams = $request->getQueryParams();

        if (!isset($queryParams[$parameterName])) {

        }

        if (!\ctype_digit($queryParams[$parameterName])) {

        }

        $parameter = (int)$queryParams[$parameterName];

        if ($parameter < 1) {

        }

        return $parameter;
    }
}
