<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Doctrine\Handlers;

use Doctrine\Instantiator\Exception\ExceptionInterface;
use Doctrine\ORM\EntityManagerInterface;
use EoneoPay\Webhooks\Bridge\Doctrine\Exceptions\EntityNotCreatedException;
use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\RequestHandlerInterface;
use EoneoPay\Webhooks\Exceptions\WebhookSequenceNotFoundException;
use EoneoPay\Webhooks\Models\WebhookRequestInterface;

final class RequestHandler implements RequestHandlerInterface
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
     *
     * @throws \EoneoPay\Webhooks\Bridge\Doctrine\Exceptions\EntityNotCreatedException
     */
    public function create(): WebhookRequestInterface
    {
        try {
            /**
             * @var \EoneoPay\Webhooks\Models\WebhookRequestInterface $instance
             */
            $instance = $this->entityManager->getClassMetadata(WebhookRequestInterface::class)
                ->newInstance();
        } /** @noinspection PhpRedundantCatchClauseInspection */ catch (ExceptionInterface $exception) {
            throw new EntityNotCreatedException(
                \sprintf(
                    'An error occurred creating an %s instance.',
                    WebhookRequestInterface::class
                ),
                0,
                $exception
            );
        }

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
         * @var \EoneoPay\Webhooks\Models\WebhookRequestInterface $entity
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
