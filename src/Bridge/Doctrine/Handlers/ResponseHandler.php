<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Doctrine\Handlers;

use Doctrine\Instantiator\Exception\ExceptionInterface;
use Doctrine\ORM\EntityManagerInterface;
use EoneoPay\Webhooks\Bridge\Doctrine\Exceptions\EntityNotCreatedException;
use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\ResponseHandlerInterface;
use EoneoPay\Webhooks\Model\WebhookResponseInterface;

class ResponseHandler implements ResponseHandlerInterface
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
    public function createNewWebhookResponse(): WebhookResponseInterface
    {
        try {
            /**
             * @var \EoneoPay\Webhooks\Model\WebhookResponseInterface $instance
             */
            $instance = $this->entityManager->getClassMetadata(WebhookResponseInterface::class)
                ->newInstance();
        } catch (ExceptionInterface $exception) {
            throw new EntityNotCreatedException(
                \sprintf(
                    'An error occurred creating an %s instance.',
                    WebhookResponseInterface::class
                ),
                0,
                $exception
            );
        }

        return $instance;
    }

    /**
     * {@inheritdoc}
     */
    public function save(WebhookResponseInterface $response): void
    {
        $this->entityManager->persist($response);
        $this->entityManager->flush();
    }
}
