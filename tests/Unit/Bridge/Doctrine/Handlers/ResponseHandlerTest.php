<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Bridge\Doctrine\Handlers;

use EoneoPay\Webhooks\Bridge\Doctrine\Entity\WebhookEntityInterface;
use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\ResponseHandler;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entity\WebhookResponseEntityStub;
use Tests\EoneoPay\Webhooks\Stubs\Vendor\Doctrine\ORM\EntityManagerStub;
use Tests\EoneoPay\Webhooks\TestCase;

/**
 * @covers \EoneoPay\Webhooks\Bridge\Doctrine\Handlers\ResponseHandler
 */
class ResponseHandlerTest extends TestCase
{
    /**
     * Create new
     *
     * @return void
     */
    public function testCreateNew(): void
    {
        // Webhook stub should be returned by EntityManager stub
        static::assertInstanceOf(WebhookResponseEntityStub::class, $this->createInstance()->createNewWebhookResponse());
    }

    /**
     * Save
     *
     * @return void
     */
    public function testSave(): void
    {
        $this->createInstance()->save(new WebhookResponseEntityStub());

        // If no exception was thrown it's all good
        $this->addToAssertionCount(1);
    }

    /**
     * Create handler instance
     *
     * @param \EoneoPay\Webhooks\Bridge\Doctrine\Entity\WebhookEntityInterface|null $entity
     *
     * @return \EoneoPay\Webhooks\Bridge\Doctrine\Handlers\ResponseHandler
     */
    private function createInstance(?WebhookEntityInterface $entity = null): ResponseHandler
    {
        return new ResponseHandler(new EntityManagerStub($entity));
    }
}
