<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Doctrine\Handlers;

use Doctrine\ORM\EntityManagerInterface;
use EoneoPay\Webhooks\Bridge\Doctrine\Entity\WebhookEntityInterface;
use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\WebhookHandlerInterface;
use EoneoPay\Webhooks\Exceptions\WebhookSequenceNotFoundException;

class WebhookHandler implements WebhookHandlerInterface
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $doctrine;

    /**
     * Constructor.
     *
     * @param \Doctrine\ORM\EntityManagerInterface $doctrine
     */
    public function __construct(EntityManagerInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @inheritdoc
     */
    public function createNewWebhook(): WebhookEntityInterface
    {
        /**
         * @var \EoneoPay\Webhooks\Bridge\Doctrine\Entity\WebhookEntityInterface $instance
         */
        $instance = $this->doctrine->getClassMetadata(WebhookEntityInterface::class)
            ->newInstance();

        return $instance;
    }

    /**
     * @inheritdoc
     *
     * @throws \EoneoPay\Webhooks\Exceptions\WebhookSequenceNotFoundException
     */
    public function getWebhook(int $sequence): WebhookEntityInterface
    {
        $entity = $this->doctrine->find(WebhookEntityInterface::class, $sequence);

        if (($entity instanceof WebhookEntityInterface) === false) {
            throw new WebhookSequenceNotFoundException(\sprintf(
                'Webhook with sequence number "%d" not found',
                $sequence
            ));
        }

        /**
         * @var \EoneoPay\Webhooks\Bridge\Doctrine\Entity\WebhookEntityInterface $entity
         *
         * @see https://youtrack.jetbrains.com/issue/WI-37859 - typehint required until PhpStorm recognises === check
         */

        return $entity;
    }

    /**
     * @inheritdoc
     */
    public function save(WebhookEntityInterface $webhook): void
    {
        $this->doctrine->persist($webhook);
        $this->doctrine->flush();
    }
}
