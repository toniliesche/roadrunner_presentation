<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Infrastructure\Http\Traits;

use ToniLiesche\Roadrunner\Infrastructure\Http\Services\RequestParser;

trait RequestParserAwareTrait
{
    private RequestParser $requestParser;

    private function getRequestParser(): RequestParser {
        if (!isset($this->requestParser)) {
            $this->requestParser = new RequestParser();
        }

        return $this->requestParser;
    }
}
