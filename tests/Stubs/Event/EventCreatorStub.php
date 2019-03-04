<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs\Event;

use EoneoPay\Webhooks\Events\Interfaces\EventCreatorInterface;
use EoneoPay\Webhooks\Events\Interfaces\EventInterface;
use EoneoPay\Webhooks\Subscription\Interfaces\SubscriptionInterface;

class EventCreatorStub implements EventCreatorInterface
{
    /**
     * @var \EoneoPay\Webhooks\Events\Interfaces\EventInterface
     */
    private $event;

    /**
     * @inheritdoc
     */
    public function create(
        string $event,
        int $sequence,
        array $payload,
        SubscriptionInterface $subscription
    ): EventInterface {
        return $this->event;
    }

    /**
     * Set event to return
     *
     * @param \EoneoPay\Webhooks\Events\Interfaces\EventInterface $event
     *
     * @return void
     */
    public function setEvent(EventInterface $event): void
    {
        $this->event = $event;
    }
}
