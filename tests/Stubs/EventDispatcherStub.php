<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs;

use EoneoPay\Externals\EventDispatcher\Interfaces\EventDispatcherInterface;

class EventDispatcherStub implements EventDispatcherInterface
{
    /**
     * @var mixed[]
     */
    private $dispatched = [];

    /**
     * @inheritdoc
     */
    public function dispatch($event, $payload = null, ?bool $halt = null): ?array
    {
        $this->dispatched[] = $event;

        return null;
    }

    /**
     * @inheritdoc
     */
    public function listen(array $events, string $listener): void
    {
    }

    /**
     * Returns dispatched events
     *
     * @return mixed[]
     */
    public function getDispatched(): array
    {
        return $this->dispatched;
    }
}
