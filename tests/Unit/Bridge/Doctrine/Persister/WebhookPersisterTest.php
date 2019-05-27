<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Bridge\Doctrine\Persister;

use EoneoPay\Externals\HttpClient\Response;
use EoneoPay\Webhooks\Bridge\Doctrine\Persister\WebhookPersister;
use EoneoPay\Webhooks\Exceptions\WebhookSequenceMissingException;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entity\WebhookEntityStub;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entity\WebhookResponseEntityStub;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Handlers\ResponseHandlerStub;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Handlers\WebhookHandlerStub;
use Tests\EoneoPay\Webhooks\Stubs\Subscription\SubscriptionStub;
use Tests\EoneoPay\Webhooks\TestCase;
use Zend\Diactoros\Response\EmptyResponse;

/**
 * @covers \EoneoPay\Webhooks\Bridge\Doctrine\Persister\WebhookPersister
 */
class WebhookPersisterTest extends TestCase
{
    /**
     * @var \EoneoPay\Webhooks\Bridge\Doctrine\Persister\WebhookPersister
     */
    private $persister;

    /**
     * @var \Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Handlers\ResponseHandlerStub
     */
    private $responseHandler;

    /**
     * @var \Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Handlers\WebhookHandlerStub
     */
    private $webhookHandler;

    /**
     * tests Save
     *
     * @return void
     */
    public function testSave(): void
    {
        $stub = new WebhookEntityStub(1);

        $this->webhookHandler->setNextWebhook($stub);

        $sequence = $this->persister->save('event', ['payload' => 'here'], new SubscriptionStub());

        static::assertSame(1, $sequence);
        static::assertContains($stub, $this->webhookHandler->getSaved());
        static::assertSame('event', $stub->getData()['event']);
        static::assertSame(['payload' => 'here'], $stub->getData()['payload']);
    }

    /**
     * tests Save without a sequence being returned
     *
     * @return void
     */
    public function testSaveNoSequence(): void
    {
        $this->expectException(WebhookSequenceMissingException::class);

        $stub = new WebhookEntityStub(null);
        $this->webhookHandler->setNextWebhook($stub);

        $this->persister->save('event', ['payload' => 'here'], new SubscriptionStub());
    }

    /**
     * tests Save without a sequence being returned
     *
     * @return void
     */
    public function testUpdate(): void
    {
        $stub = new WebhookEntityStub(null);
        $this->webhookHandler->setNextWebhook($stub);

        $responseStub = new WebhookResponseEntityStub();
        $this->responseHandler->setNextResponse($responseStub);

        $this->persister->update(1, new Response(new EmptyResponse()));

        static::assertContains($responseStub, $this->responseHandler->getSaved());
        static::assertSame($stub, $responseStub->getData()['webhook']);
    }

    /**
     * Set up
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->webhookHandler = new WebhookHandlerStub();
        $this->responseHandler = new ResponseHandlerStub();

        $this->persister = new WebhookPersister($this->webhookHandler, $this->responseHandler);
    }
}
