<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Bridge\Doctrine\Handlers;

use Doctrine\Instantiator\Exception\ExceptionInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use EoneoPay\Webhooks\Bridge\Doctrine\Exceptions\EntityNotCreatedException;
use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\ResponseHandler;
use EoneoPay\Webhooks\Models\WebhookRequestInterface;
use EoneoPay\Webhooks\Models\WebhookResponseInterface;
use Exception;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entities\Webhooks\ResponseStub;
use Tests\EoneoPay\Webhooks\Stubs\Vendor\Doctrine\ORM\EntityManagerStub;
use Tests\EoneoPay\Webhooks\TestCase;

/**
 * @covers \EoneoPay\Webhooks\Bridge\Doctrine\Handlers\ResponseHandler
 */
class ResponseHandlerTest extends TestCase
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
            WebhookResponseInterface::class
        ));

        $classMetadata = $this->createMock(ClassMetadata::class);
        $classMetadata->expects(self::once())
            ->method('newInstance')
            ->willThrowException(new class() extends Exception implements ExceptionInterface {
            });

        $responseHandler = $this->createInstance(null, $classMetadata);
        $response = $responseHandler->createNewWebhookResponse();

        self::assertInstanceOf(ResponseStub::class, $response);
    }

    /**
     * Create new.
     *
     * @return void
     */
    public function testCreateNew(): void
    {
        // Webhook stub should be returned by EntityManager stub
        $responseHandler = $this->createInstance();
        $response = $responseHandler->createNewWebhookResponse();

        self::assertInstanceOf(ResponseStub::class, $response);
    }

    /**
     * Save.
     *
     * @return void
     */
    public function testSave(): void
    {
        $responseHandler = $this->createInstance();
        $responseHandler->save(new ResponseStub());

        // If no exception was thrown it's all good
        $this->addToAssertionCount(1);
    }

    /**
     * Create handler instance.
     *
     * @param \EoneoPay\Webhooks\Models\WebhookRequestInterface|null $entity
     * @param \Doctrine\ORM\Mapping\ClassMetadata $classMetadata
     *
     * @return \EoneoPay\Webhooks\Bridge\Doctrine\Handlers\ResponseHandler
     */
    private function createInstance(
        ?WebhookRequestInterface $entity = null,
        ?ClassMetadata $classMetadata = null
    ): ResponseHandler {
        return new ResponseHandler(new EntityManagerStub(
            $entity,
            [WebhookResponseInterface::class => $classMetadata ?? new ClassMetadata(ResponseStub::class)]
        ));
    }
}
