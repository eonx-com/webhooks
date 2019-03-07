<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Bridge\Doctrine\Persister;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use EoneoPay\Webhooks\Bridge\Doctrine\Entity\WebhookEntityInterface;
use EoneoPay\Webhooks\Bridge\Doctrine\Persister\WebhookPersister;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entity\WebhookEntityStub;
use Tests\EoneoPay\Webhooks\Stubs\Subscription\SubscriptionStub;
use Tests\EoneoPay\Webhooks\TestCase;

class WebhookPersisterTest extends TestCase
{
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
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function testSave(): void
    {
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

        $classMetadata = $this->createMock(ClassMetadataInfo::class);
        $classMetadata
            ->method('newInstance')
            ->willReturnCallback(function (): WebhookEntityInterface {
                return new WebhookEntityStub(1);
            });

        $this->doctrine = $this->createMock(EntityManagerInterface::class);
        $this->doctrine
            ->method('getClassMetadata')
            ->with(WebhookEntityInterface::class)
            ->willReturn($classMetadata);

        $this->persister = new WebhookPersister($this->doctrine);
    }
}
