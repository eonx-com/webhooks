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
     * {@inheritdoc}
     */
    public function alert($message, ?array $context = null): void
    {
        $this->logs[] = \compact('message', 'context');
    }

    /**
     * {@inheritdoc}
     */
    public function critical($message, ?array $context = null): void
    {
        $this->logs[] = \compact('message', 'context');
    }

    /**
     * {@inheritdoc}
     */
    public function debug($message, ?array $context = null): void
    {
        $this->logs[] = \compact('message', 'context');
    }

    /**
     * {@inheritdoc}
     */
    public function emergency($message, ?array $context = null): void
    {
        $this->logs[] = \compact('message', 'context');
    }

    /**
     * {@inheritdoc}
     */
    public function error($message, ?array $context = null): void
    {
        $this->logs[] = \compact('message', 'context');
    }

    /**
     * {@inheritdoc}
     */
    public function exception(Exception $exception, ?string $level = null): void
    {
        $this->logs[] = \compact('exception', 'level');
    }

    /**
     * @return mixed[]
     */
    public function getLogs(): array
    {
        return $this->logs;
    }

    /**
     * {@inheritdoc}
     */
    public function info($message, ?array $context = null): void
    {
        $this->logs[] = \compact('message', 'context');
    }

    /**
     * {@inheritdoc}
     */
    public function log($level, $message, ?array $context = null): void
    {
        $this->logs[] = \compact('message', 'context');
    }

    /**
     * {@inheritdoc}
     */
    public function notice($message, ?array $context = null): void
    {
        $this->logs[] = \compact('message', 'context');
    }

    /**
     * {@inheritdoc}
     */
    public function warning($message, ?array $context = null): void
    {
        $this->logs[] = \compact('message', 'context');
    }
}