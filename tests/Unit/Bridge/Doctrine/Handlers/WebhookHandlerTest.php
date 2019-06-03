<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Bridge\Doctrine\Handlers;

use EoneoPay\Webhooks\Bridge\Doctrine\Entity\WebhookEntityInterface;
use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\WebhookHandler;
use EoneoPay\Webhooks\Exceptions\WebhookSequenceNotFoundException;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entity\WebhookEntityStub;
use Tests\EoneoPay\Webhooks\Stubs\Vendor\Doctrine\ORM\EntityManagerStub;
use Tests\EoneoPay\Webhooks\TestCase;

/**
 * @covers \EoneoPay\Webhooks\Bridge\Doctrine\Handlers\WebhookHandler
 */
class WebhookHandlerTest extends TestCase
{
    /**
     * Create new webhook from interface
     *
     * @return void
     */
    public function testCreateNew(): void
    {
        // Webhook stub should be returned by EntityManager stub
        static::assertInstanceOf(WebhookEntityStub::class, $this->createInstance()->createNewWebhook());
    }

    /**
     * Get webhook
     *
     * @return void
     */
    public function testGetWebhook(): void
    {
        $stub = new WebhookEntityStub(1);

        static::assertSame($stub, $this->createInstance($stub)->getWebhook(1));
    }

    /**
     * Get webhook not found
     *
     * @return void
     */
    public function testGetWebhookNotFound(): void
    {
        $this->expectException(WebhookSequenceNotFoundException::class);

        $this->createInstance()->getWebhook(1);
    }

    /**
     * Save
     *
     * @return void
     */
    public function testSave(): void
    {
        $this->createInstance()->save(new WebhookEntityStub(null));

        // If no exception is thrown it's all good
        $this->addToAssertionCount(1);
    }

    /**
     * Create handler instance
     *
     * @param \EoneoPay\Webhooks\Bridge\Doctrine\Entity\WebhookEntityInterface|null $entity
     *
     * @return \EoneoPay\Webhooks\Bridge\Doctrine\Handlers\WebhookHandler
     */
    private function createInstance(?WebhookEntityInterface $entity = null): WebhookHandler
    {
        return new WebhookHandler(new EntityManagerStub($entity));
    }
}