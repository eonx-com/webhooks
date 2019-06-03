<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs\Event;

use EoneoPay\Webhooks\Bridge\Laravel\Events\Event;
use EoneoPay\Webhooks\Events\Interfaces\EventCreatorInterface;
use EoneoPay\Webhooks\Events\Interfaces\EventInterface;
use EoneoPay\Webhooks\Subscription\Interfaces\SubscriptionInterface;

class EventCreatorStub implements EventCreatorInterface
{
    /**
     * @var \EoneoPay\Webhooks\Events\Interfaces\EventInterface[]
     */
    private $events;

    /**
     * Set event to return
     *
     * @param \EoneoPay\Webhooks\Events\Interfaces\EventInterface $event
     *
     * @return void
     */
    public function addEvent(EventInterface $event): void
    {
        $this->events[] = $event;
    }

    /**
     * {@inheritdoc}
     */
    public function create(
        string $event,
        array $payload,
        SubscriptionInterface $subscription
    ): EventInterface {
        return \array_shift($this->events) ?? new Event('', '', 0, '');
    }
}
