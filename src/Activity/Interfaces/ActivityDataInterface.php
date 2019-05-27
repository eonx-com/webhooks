<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Activity\Interfaces;

interface ActivityDataInterface
{
    /**
     * Returns the webhook event constant.
     *
     * @return string
     */
    public function getEvent(): string;

    /**
     * Returns a payload array of array serialised data to be sent
     * as part of the webhook.
     *
     * @return mixed[]
     */
    public function getPayload(): array;

    /**
     * Returns any subscribers that are relevant to the webhook being
     * fired.
     *
     * @return \EoneoPay\Webhooks\Subscription\Interfaces\SubscriberInterface[]
     */
    public function getSubscribers(): array;
}
