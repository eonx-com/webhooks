<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs\Event;

use EoneoPay\Webhooks\Events\Interfaces\EventInterface;
use EoneoPay\Webhooks\Events\Interfaces\WebhookEventDispatcherInterface;

class WebhookEventDispatcherStub implements WebhookEventDispatcherInterface
{
    /**
     * @var \EoneoPay\Webhooks\Events\Interfaces\EventInterface[]
     */
    private $dispatched = [];

    /**
     * @inheritdoc
     */
    public function dispatch(EventInterface $event): void
    {
        $this->dispatched[] = $event;
    }

    /**
     * @return \EoneoPay\Webhooks\Events\Interfaces\EventInterface[]
     */
    public function getDispatched(): array
    {
        return $this->dispatched;
    }
}
