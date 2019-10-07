<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Bridge\Doctrine\Handlers;

use Doctrine\Instantiator\Exception\ExceptionInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use EoneoPay\Webhooks\Bridge\Doctrine\Exceptions\EntityNotCreatedException;
use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\RequestHandler;
use EoneoPay\Webhooks\Exceptions\WebhookSequenceNotFoundException;
use EoneoPay\Webhooks\Models\WebhookRequestInterface;
use Exception;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entities\Webhooks\RequestStub;
use Tests\EoneoPay\Webhooks\Stubs\Vendor\Doctrine\ORM\EntityManagerStub;
use Tests\EoneoPay\Webhooks\TestCase;

/**
 * @covers \EoneoPay\Webhooks\Bridge\Doctrine\Handlers\RequestHandler
 */
class RequestHandlerTest extends TestCase
{
    /**
     * Create new fails.
     *
     * @return void
     */
    public function testCreateFails(): void
    {
        $this->expectException(EntityNotCreatedException::class);
        $this->expectExceptionMessage(\sprintf(
            'An error occurred creating an %s instance.',
            WebhookRequestInterface::class
        ));

        $classMetadata = $this->createMock(ClassMetadata::class);
        $classMetadata->expects(self::once())
            ->method('newInstance')
            ->willThrowException(new class() extends Exception implements ExceptionInterface {
            });

        $requestHandler = $this->createInstance(null, $classMetadata);
        $requestHandler->create();
    }

    /**
     * Create new webhook from interface.
     *
     * @return void
     */
    public function testCreateNew(): void
    {
        $requestHandler = $this->createInstance();
        $request = $requestHandler->create();

        self::assertInstanceOf(RequestStub::class, $request);
    }

    /**
     * Get webhook.
     *
     * @return void
     */
    public function testGetWebhook(): void
    {
        $stub = new RequestStub(1);

        $requestHandler = $this->createInstance($stub);
        $request = $requestHandler->getBySequence(1);

        self::assertSame($stub, $request);
    }

    /**
     * Get webhook not found.
     *
     * @return void
     */
    public function testGetWebhookNotFound(): void
    {
        $this->expectException(WebhookSequenceNotFoundException::class);

        $requestHandler = $this->createInstance();
        $requestHandler->getBySequence(1);
    }

    /**
     * Save.
     *
     * @return void
     */
    public function testSave(): void
    {
        $requestHandler = $this->createInstance();
        $requestHandler->save(new RequestStub(null));

        // If no exception is thrown it's all good
        $this->addToAssertionCount(1);
    }

    /**
     * Create handler instance.
     *
     * @param \EoneoPay\Webhooks\Models\WebhookRequestInterface|null $entity
     * @param \Doctrine\ORM\Mapping\ClassMetadata $classMetadata
     *
     * @return \EoneoPay\Webhooks\Bridge\Doctrine\Handlers\RequestHandler
     */
    private function createInstance(
        ?WebhookRequestInterface $entity = null,
        ?ClassMetadata $classMetadata = null
    ): RequestHandler {
        return new RequestHandler(new EntityManagerStub(
            $entity,
            [WebhookRequestInterface::class => $classMetadata ?? new ClassMetadata(RequestStub::class)]
        ));
    }
}
