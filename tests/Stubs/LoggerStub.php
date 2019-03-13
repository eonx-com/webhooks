<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs;

use EoneoPay\Externals\Logger\Interfaces\LoggerInterface;
use Exception;

class LoggerStub implements LoggerInterface
{
    /**
     * @var mixed[]
     */
    private $logs = [];

    /**
     * @inheritdoc
     */
    public function debug(string $message, ?array $context = null): bool
    {
        $this->logs[] = \compact('message', 'context');

        return true;
    }

    /**
     * @inheritdoc
     */
    public function error(string $message, ?array $context = null): bool
    {
        $this->logs[] = \compact('message', 'context');

        return true;
    }

    /**
     * @inheritdoc
     */
    public function exception(Exception $exception, ?string $level = null): bool
    {
        $this->logs[] = \compact('exception', 'level');

        return true;
    }

    /**
     * @return mixed[]
     */
    public function getLogs(): array
    {
        return $this->logs;
    }

    /**
     * @inheritdoc
     */
    public function info(string $message, ?array $context = null): bool
    {
        $this->logs[] = \compact('message', 'context');

        return true;
    }

    /**
     * @inheritdoc
     */
    public function notice(string $message, ?array $context = null): bool
    {
        $this->logs[] = \compact('message', 'context');

        return true;
    }

    /**
     * @inheritdoc
     */
    public function warning(string $message, ?array $context = null): bool
    {
        $this->logs[] = \compact('message', 'context');

        return true;
    }
}
