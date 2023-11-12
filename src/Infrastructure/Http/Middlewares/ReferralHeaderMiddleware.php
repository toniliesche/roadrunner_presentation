<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Infrastructure\Http\Middlewares;

use Closure;
use GuzzleHttp\Promise\FulfilledPromise;
use Psr\Http\Message\RequestInterface;

readonly final class ReferralHeaderMiddleware
{
    public function __construct(private string $referralHeader, private ?Closure $referralIdProvider)
    {

    }
    public function __invoke(callable $handler): Closure
    {
        return function (RequestInterface $request, array $options) use ($handler): FulfilledPromise {
            $referralHeader = $this->referralHeader;
            if (\array_key_exists($referralHeader, $_SERVER)) {
                return $handler($request->withHeader($referralHeader, $_SERVER[$referralHeader]), $options);
            }

            if (isset($this->referralIdProvider) && null !== ($referralId = \call_user_func($this->referralIdProvider))) {
                return $handler($request->withHeader($referralHeader, $referralId), $options);
            }

            return $handler($request, $options);
        };
    }
}
