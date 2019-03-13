<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Doctrine\Handlers;

use Doctrine\ORM\EntityManagerInterface;
use EoneoPay\Webhooks\Bridge\Doctrine\Entity\WebhookResponseEntityInterface;
use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\ResponseHandlerInterface;

class ResponseHandler implements ResponseHandlerInterface
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
    public function createNewWebhookResponse(): WebhookResponseEntityInterface
    {
        /**
         * @var \EoneoPay\Webhooks\Bridge\Doctrine\Entity\WebhookResponseEntityInterface $instance
         */
        $instance = $this->doctrine->getClassMetadata(WebhookResponseEntityInterface::class)
            ->newInstance();

        return $instance;
    }

    /**
     * @inheritdoc
     */
    public function save(WebhookResponseEntityInterface $response): void
    {
        $this->doctrine->persist($response);
        $this->doctrine->flush();
    }
}
