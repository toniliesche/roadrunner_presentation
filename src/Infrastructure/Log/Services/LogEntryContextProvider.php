<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Infrastructure\Log\Services;

use ToniLiesche\Roadrunner\Core\Application\Framework\Services\RequestIdService;
use ToniLiesche\Roadrunner\Infrastructure\Log\Enums\LogCategory;

readonly final class LogEntryContextProvider
{
    public function __construct(
        private RequestIdService $requestIdService,
        private string $application,
        private string $stage,
        private string $host,
        private int $pid
    ) {
    }

    public function populateContext(
        array $context,
        string $logLevel,
        LogCategory|string|null $logCategory = null
    ): array {
        $data = [];
        if (null !== $logCategory) {
            if ($logCategory instanceof LogCategory) {
                $data['category'] = $logCategory->value;
            } else {
                $data['category'] = $logCategory;
            }
        }

        $data += [
            'logLevel' => $logLevel,
            'application' => $this->application,
            'stage' => $this->stage,
            'host' => $this->host,
            'processId' => $this->pid,
            'requestId' => $this->requestIdService->getRequestId(),
            'referralId' => $this->requestIdService->getReferralId(),
        ];


        foreach (['application', 'category', 'host', 'stage'] as $key) {
            if (!isset($context[$key])) {
                continue;
            }

            $i = 1;
            while (true) {
                $newKey = \sprintf('%s_%d', $key, $i);
                if (isset($context[$newKey])) {
                    $i++;
                    continue;
                }

                $context[$newKey] = $context[$key];
                break;
            }
            unset($context[$key]);
        }

        return $data + $context;
    }

    public function getApplication(): string
    {
        return $this->application;
    }

    public function getStage(): string
    {
        return $this->stage;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPid(): int
    {
        return $this->pid;
    }
}
