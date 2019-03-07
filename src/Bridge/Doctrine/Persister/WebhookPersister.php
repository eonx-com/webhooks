<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Doctrine\Persister;

use Doctrine\ORM\EntityManagerInterface;
use EoneoPay\Webhooks\Bridge\Doctrine\Entity\WebhookEntityInterface;
use EoneoPay\Webhooks\Persister\Interfaces\WebhookPersisterInterface;
use EoneoPay\Webhooks\Subscription\Interfaces\SubscriptionInterface;

final class WebhookPersister implements WebhookPersisterInterface
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $doctrine;

    /**
     * WebhookPersister constructor.
     *
     * @param \Doctrine\ORM\EntityManagerInterface $doctrine
     */
    public function __construct(EntityManagerInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @inheritdoc
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function save(string $event, array $payload, SubscriptionInterface $subscription): int
    {
        $webhook = $this->createNewWebhook();
        $webhook->populate($event, $payload, $subscription);

        $this->doctrine->persist($webhook);
        $this->doctrine->flush();

        return $webhook->getSequence();
    }

    /**
     * Creates a new real instance of WebhookEntityInterface
     *
     * @return \EoneoPay\Webhooks\Bridge\Doctrine\Entity\WebhookEntityInterface
     */
    private function createNewWebhook(): WebhookEntityInterface
    {
        /**
         * @var \EoneoPay\Webhooks\Bridge\Doctrine\Entity\WebhookEntityInterface $instance
         */
        $instance = $this->doctrine->getClassMetadata(WebhookEntityInterface::class)
            ->newInstance();

        return $instance;
    }
}
