<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Bridge\Doctrine\Handlers;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use EoneoPay\Webhooks\Bridge\Doctrine\Entity\WebhookEntityInterface;
use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\WebhookHandler;
use EoneoPay\Webhooks\Exceptions\WebhookSequenceNotFoundException;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entity\WebhookEntityStub;
use Tests\EoneoPay\Webhooks\TestCase;

/**
 * @covers \EoneoPay\Webhooks\Bridge\Doctrine\Handlers\WebhookHandler
 */
class WebhookHandlerTest extends TestCase
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
     * @var \EoneoPay\Webhooks\Bridge\Doctrine\Handlers\WebhookHandler
     */
    private $repository;

    /**
     * create new
     *
     * @return void
     */
    public function testCreateNew(): void
    {
        $stub = new WebhookEntityStub(1);

        $this->classMetadata
            ->method('newInstance')
            ->willReturnCallback(function () use ($stub): WebhookEntityInterface {
                return $stub;
            });

        $result = $this->repository->createNewWebhook();

        static::assertSame($stub, $result);
    }

    /**
     * Get webhook
     *
     * @return void
     */
    public function testGetWebhook(): void
    {
        $stub = new WebhookEntityStub(1);

        $this->doctrine->expects(static::once())
            ->method('find')
            ->with(WebhookEntityInterface::class, 1)
            ->willReturn($stub);

        $result = $this->repository->getWebhook(1);

        static::assertSame($stub, $result);
    }

    /**
     * Get webhook not found
     *
     * @return void
     */
    public function testGetWebhookNotFound(): void
    {
        $this->expectException(WebhookSequenceNotFoundException::class);

        $this->doctrine->expects(static::once())
            ->method('find')
            ->with(WebhookEntityInterface::class, 1)
            ->willReturn(null);

        $this->repository->getWebhook(1);
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
            ->with(static::isInstanceOf(WebhookEntityInterface::class));
        $this->doctrine->expects(static::once())
            ->method('flush');

        $this->repository->save(new WebhookEntityStub(null));
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
            ->with(WebhookEntityInterface::class)
            ->willReturn($this->classMetadata);

        $this->repository = new WebhookHandler($this->doctrine);
    }
}
