<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Bridge\Doctrine\Handlers;

use Doctrine\Instantiator\Exception\ExceptionInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use EoneoPay\Webhooks\Bridge\Doctrine\Exceptions\EntityNotCreatedException;
use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\ActivityHandler;
use EoneoPay\Webhooks\Model\ActivityInterface;
use Exception;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entity\ActivityStub;
use Tests\EoneoPay\Webhooks\Stubs\Vendor\Doctrine\ORM\EntityManagerStub;
use Tests\EoneoPay\Webhooks\TestCase;

/**
 * @covers \EoneoPay\Webhooks\Bridge\Doctrine\Handlers\ActivityHandler
 */
class ActivityHandlerTest extends TestCase
{
    /**
     * Create new webhook from interface
     *
     * @return void
     */
    public function testCreateNew(): void
    {
        $requestHandler = $this->createInstance();
        $request = $requestHandler->create();

        static::assertInstanceOf(ActivityStub::class, $request);
    }

    /**
     * Create new fails
     *
     * @return void
     */
    public function testCreateFails(): void
    {
        $this->expectException(EntityNotCreatedException::class);
        $this->expectExceptionMessage('An error occurred creating an EoneoPay\Webhooks\Model\ActivityInterface instance.'); // phpcs:ignore

        $classMetadata = $this->createMock(ClassMetadata::class);
        $classMetadata->expects(static::once())
            ->method('newInstance')
            ->willThrowException(new class extends Exception implements ExceptionInterface
            {
            });

        $requestHandler = $this->createInstance($classMetadata);
        $requestHandler->create();
    }

    /**
     * Save
     *
     * @return void
     */
    public function testSave(): void
    {
        $requestHandler = $this->createInstance();
        $requestHandler->save(new ActivityStub());

        // If no exception is thrown it's all good
        $this->addToAssertionCount(1);
    }

    /**
     * Create handler instance
     *
     * @param \Doctrine\ORM\Mapping\ClassMetadata $classMetadata
     *
     * @return \EoneoPay\Webhooks\Bridge\Doctrine\Handlers\ActivityHandler
     */
    private function createInstance(
        ?ClassMetadata $classMetadata = null
    ): ActivityHandler {
        return new ActivityHandler(new EntityManagerStub(
            null,
            [ActivityInterface::class => $classMetadata ?? new ClassMetadata(ActivityStub::class)]
        ));
    }
}
