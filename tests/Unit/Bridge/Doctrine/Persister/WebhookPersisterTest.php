<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Bridge\Doctrine\Persister;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use EoneoPay\Webhooks\Bridge\Doctrine\Entity\WebhookEntityInterface;
use EoneoPay\Webhooks\Bridge\Doctrine\Persister\WebhookPersister;
use EoneoPay\Webhooks\Exceptions\WebhookSequenceMissingException;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entity\WebhookEntityStub;
use Tests\EoneoPay\Webhooks\Stubs\Subscription\SubscriptionStub;
use Tests\EoneoPay\Webhooks\TestCase;

class WebhookPersisterTest extends TestCase
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
     * @var \EoneoPay\Webhooks\Bridge\Doctrine\Persister\WebhookPersister
     */
    private $persister;

    /**
     * tests Save
     *
     * @return void
     */
    public function testSave(): void
    {
        $this->classMetadata
            ->method('newInstance')
            ->willReturnCallback(function (): WebhookEntityInterface {
                return new WebhookEntityStub(1);
            });

        $this->doctrine->expects(static::once())
            ->method('persist')
            ->with(static::isInstanceOf(WebhookEntityInterface::class));
        $this->doctrine->expects(static::once())
            ->method('flush');

        $sequence = $this->persister->save('event', ['payload' => 'here'], new SubscriptionStub());

        static::assertEquals(1, $sequence);
    }

    /**
     * tests Save without a sequence being returned
     *
     * @return void
     */
    public function testSaveNoSequence(): void
    {
        $this->expectException(WebhookSequenceMissingException::class);

        $this->classMetadata
            ->method('newInstance')
            ->willReturnCallback(function (): WebhookEntityInterface {
                return new WebhookEntityStub(null);
            });

        $this->doctrine->expects(static::once())
            ->method('persist')
            ->with(static::isInstanceOf(WebhookEntityInterface::class));
        $this->doctrine->expects(static::once())
            ->method('flush');

        $sequence = $this->persister->save('event', ['payload' => 'here'], new SubscriptionStub());

        static::assertEquals(1, $sequence);
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

        $this->persister = new WebhookPersister($this->doctrine);
    }
}
