<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Infrastructure\Engine\Factories;

use Psr\Container\ContainerInterface;
use Spiral\Goridge\RPC\RPC;
use Spiral\RoadRunner\Environment;

class RPCFactory
{
    public function __invoke(ContainerInterface $container): RPC
    {
        return RPC::create(Environment::fromGlobals()->getRPCAddress());
    }
}
