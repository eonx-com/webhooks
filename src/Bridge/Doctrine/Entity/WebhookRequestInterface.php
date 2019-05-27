<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Doctrine\Entity;

use EoneoPay\Webhooks\Subscription\Interfaces\SubscriptionInterface;

interface WebhookRequestInterface
{
    /**
     * Returns the activity that raised this webhook request.
     *
     * @return \EoneoPay\Webhooks\Bridge\Doctrine\Entity\ActivityInterface
     */
    public function getActivity(): ActivityInterface;

    /**
     * Returns the sequence number for this webhook request.
     *
     * In an implementation, typically this would be the
     * autoincrement primary key.
     *
     * @return int|null
     */
    public function getSequence(): ?int;

    /**
     * Populates a WebhookRequest with data.
     *
     * @param \EoneoPay\Webhooks\Bridge\Doctrine\Entity\ActivityInterface $activity
     * @param \EoneoPay\Webhooks\Subscription\Interfaces\SubscriptionInterface $subscription
     *
     * @return void
     */
    public function populate(ActivityInterface $activity, SubscriptionInterface $subscription): void;
}
