<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Bridge\Doctrine\Handlers;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use EoneoPay\Webhooks\Bridge\Doctrine\Entity\WebhookResponseEntityInterface;
use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\ResponseHandler;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entity\WebhookResponseEntityStub;
use Tests\EoneoPay\Webhooks\TestCase;

/**
 * @covers \EoneoPay\Webhooks\Bridge\Doctrine\Handlers\ResponseHandler
 */
class ResponseHandlerTest extends TestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $classMetadata;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $doctrine;

    /**
     * @var \EoneoPay\Webhooks\Bridge\Doctrine\Handlers\ResponseHandler
     */
    private $repository;

    /**
     * create new
     *
     * @return void
     */
    public function testCreateNew(): void
    {
        $stub = new WebhookResponseEntityStub();

        $this->classMetadata
            ->method('newInstance')
            ->willReturnCallback(function () use ($stub): WebhookResponseEntityInterface {
                return $stub;
            });

        $result = $this->repository->createNewWebhookResponse();

        static::assertSame($stub, $result);
    }

    /**
     * Save
     *
     * @return void
     */
    public function testSave(): void
    {
        $this->doctrine->expects(static::once())
            ->method('persist')
            ->with(static::isInstanceOf(WebhookResponseEntityInterface::class));
        $this->doctrine->expects(static::once())
            ->method('flush');

        $this->repository->save(new WebhookResponseEntityStub());
    }

    /**
     * Set up
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->classMetadata = $this->createMock(ClassMetadataInfo::class);

        $this->doctrine = $this->createMock(EntityManagerInterface::class);
        $this->doctrine
            ->method('getClassMetadata')
            ->with(WebhookResponseEntityInterface::class)
            ->willReturn($this->classMetadata);

        $this->repository = new ResponseHandler($this->doctrine);
    }
}
