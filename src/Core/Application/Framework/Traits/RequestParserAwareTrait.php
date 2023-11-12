<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Framework\Traits;

use ToniLiesche\Roadrunner\Core\Application\Framework\Services\RequestParser;

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
