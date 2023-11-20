<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Core\Application\Config\Models;

use ToniLiesche\Roadrunner\Core\Application\Config\Exceptions\MissingConfigValueException;
use ToniLiesche\Roadrunner\Core\Application\Library\Enums\PHPRuntime;

readonly final class SystemConfig
{
    private string $host;

    private string $stage;

    private PHPRuntime $runtime;

    private string $referralHeader;

    /**
     * @throws MissingConfigValueException
     */
    public function __construct(array $data = [])
    {
        $this->host = $data['host'] ?? 'unknown host';
        $this->stage = $data['stage'] ?? 'unknown stage';
        $this->referralHeader = $data['referralHeader'] ?? 'X-Request-Id';

        $this->runtime = $data['runtime'] ?? throw new MissingConfigValueException(
            'Missing mandatory setting "runtime" in system config section'
        );
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getStage(): string
    {
        return $this->stage;
    }

    public function getRuntime(): PHPRuntime
    {
        return $this->runtime;
    }

    public function getReferralHeader(): string
    {
        return $this->referralHeader;
    }
}
