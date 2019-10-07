<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Bridge\Doctrine\Handlers;

use Doctrine\Instantiator\Exception\ExceptionInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use EoneoPay\Webhooks\Bridge\Doctrine\Exceptions\DoctrineMisconfiguredException;
use EoneoPay\Webhooks\Bridge\Doctrine\Exceptions\EntityNotCreatedException;
use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\ActivityHandler;
use EoneoPay\Webhooks\Models\ActivityInterface;
use Exception;
use stdClass;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entities\ActivityStub;
use Tests\EoneoPay\Webhooks\Stubs\Vendor\Doctrine\ORM\EntityManagerStub;
use Tests\EoneoPay\Webhooks\TestCase;

/**
 * @covers \EoneoPay\Webhooks\Bridge\Doctrine\Handlers\ActivityHandler
 */
class ActivityHandlerTest extends TestCase
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
            ActivityInterface::class
        ));

        $classMetadata = $this->createMock(ClassMetadata::class);
        $classMetadata->expects(self::once())
            ->method('newInstance')
            ->willThrowException(new class() extends Exception implements ExceptionInterface {
            });

        $requestHandler = $this->createInstance($classMetadata);
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

        self::assertInstanceOf(ActivityStub::class, $request);
    }

    /**
     * Tests get failure.
     *
     * @return void
     */
    public function testGetFailure(): void
    {
        $this->expectException(DoctrineMisconfiguredException::class);

        $requestHandler = $this->createInstance(null, new stdClass());
        $requestHandler->get(5);
    }

    /**
     * Tests get success.
     *
     * @return void
     */
    public function testGetSuccess(): void
    {
        $activity = new ActivityStub();

        $requestHandler = $this->createInstance(null, $activity);
        $result = $requestHandler->get(5);

        self::assertSame($activity, $result);
    }

    /**
     * Tests get success.
     *
     * @return void
     */
    public function testGetSuccessNull(): void
    {
        $requestHandler = $this->createInstance();
        $result = $requestHandler->get(5);

        self::assertNull($result);
    }

    /**
     * Save.
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
     * Create handler instance.
     *
     * @param \Doctrine\ORM\Mapping\ClassMetadata $classMetadata
     * @param mixed $activity
     *
     * @return \EoneoPay\Webhooks\Bridge\Doctrine\Handlers\ActivityHandler
     */
    private function createInstance(
        ?ClassMetadata $classMetadata = null,
        $activity = null
    ): ActivityHandler {
        return new ActivityHandler(new EntityManagerStub(
            $activity,
            [ActivityInterface::class => $classMetadata ?? new ClassMetadata(ActivityStub::class)]
        ));
    }
}
