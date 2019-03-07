<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Client\Interfaces;

use EoneoPay\Webhooks\Events\Interfaces\EventInterface;

interface ClientInterface
{
    /**
     * Sends an event.
     *
     * @param \EoneoPay\Webhooks\Events\Interfaces\EventInterface $event
     *
     * @return void
     */
    public function send(EventInterface $event): void;
}
