<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Doctrine\Handlers;

use Doctrine\ORM\EntityManagerInterface;
use EoneoPay\Webhooks\Bridge\Doctrine\Entity\WebhookRequestInterface;
use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\RequestHandlerInterface;
use EoneoPay\Webhooks\Exceptions\WebhookSequenceNotFoundException;

class RequestHandler implements RequestHandlerInterface
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $entityManager;

    /**
     * Constructor.
     *
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function create(): WebhookRequestInterface
    {
        /**
         * @var \EoneoPay\Webhooks\Bridge\Doctrine\Entity\WebhookRequestInterface $instance
         */
        $instance = $this->entityManager->getClassMetadata(WebhookRequestInterface::class)
            ->newInstance();

        return $instance;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \EoneoPay\Webhooks\Exceptions\WebhookSequenceNotFoundException
     */
    public function getBySequence(int $sequence): WebhookRequestInterface
    {
        $entity = $this->entityManager->find(WebhookRequestInterface::class, $sequence);

        if (($entity instanceof WebhookRequestInterface) === false) {
            throw new WebhookSequenceNotFoundException(\sprintf(
                'Webhook with sequence number "%d" not found',
                $sequence
            ));
        }

        /**
         * @var \EoneoPay\Webhooks\Bridge\Doctrine\Entity\WebhookRequestInterface $entity
         *
         * @see https://youtrack.jetbrains.com/issue/WI-37859 - typehint required until PhpStorm recognises === check
         */

        return $entity;
    }

    /**
     * {@inheritdoc}
     */
    public function save(WebhookRequestInterface $webhook): void
    {
        $this->entityManager->persist($webhook);
        $this->entityManager->flush();
    }
}
