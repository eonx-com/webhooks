<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Bridge\Doctrine\Handlers;

use EoneoPay\Webhooks\Bridge\Doctrine\Entity\WebhookRequestInterface;
use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\RequestHandler;
use EoneoPay\Webhooks\Exceptions\WebhookSequenceNotFoundException;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entity\WebhookRequestStub;
use Tests\EoneoPay\Webhooks\Stubs\Vendor\Doctrine\ORM\EntityManagerStub;
use Tests\EoneoPay\Webhooks\TestCase;

/**
 * @covers \EoneoPay\Webhooks\Bridge\Doctrine\Handlers\RequestHandler
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
        static::assertInstanceOf(WebhookRequestStub::class, $this->createInstance()->create());
    }

    /**
     * Get webhook
     *
     * @return void
     */
    public function testGetWebhook(): void
    {
        $stub = new WebhookRequestStub(1);

        static::assertSame($stub, $this->createInstance($stub)->getBySequence(1));
    }

    /**
     * Get webhook not found
     *
     * @return void
     */
    public function testGetWebhookNotFound(): void
    {
        $this->expectException(WebhookSequenceNotFoundException::class);

        $this->createInstance()->getBySequence(1);
    }

    /**
     * Save
     *
     * @return void
     */
    public function testSave(): void
    {
        $this->createInstance()->save(new WebhookRequestStub(null));

        // If no exception is thrown it's all good
        $this->addToAssertionCount(1);
    }

    /**
     * Create handler instance
     *
     * @param \EoneoPay\Webhooks\Bridge\Doctrine\Entity\WebhookRequestInterface|null $entity
     *
     * @return \EoneoPay\Webhooks\Bridge\Doctrine\Handlers\RequestHandler
     */
    private function createInstance(?WebhookRequestInterface $entity = null): RequestHandler
    {
        return new RequestHandler(new EntityManagerStub($entity));
    }
}
