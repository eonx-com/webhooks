<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs\Client;

use EoneoPay\Webhooks\Client\Interfaces\ClientInterface;
use EoneoPay\Webhooks\Events\Interfaces\EventInterface;

class ClientStub implements ClientInterface
{
    /**
     * @var \EoneoPay\Webhooks\Events\Interfaces\EventInterface[]
     */
    private $sent = [];

    /**
     * @return \EoneoPay\Webhooks\Events\Interfaces\EventInterface[]
     */
    public function getSent(): array
    {
        return $this->sent;
    }

    /**
     * {@inheritdoc}
     */
    public function send(EventInterface $event): void
    {
        $this->sent[] = $event;
    }
}
