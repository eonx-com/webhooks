<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs\Externals;

use EoneoPay\Externals\ORM\Entity;

/**
 * @coversNothing
 */
class EntityStub extends Entity
{
    /**
     * The entity id
     *
     * @var int
     */
    protected $entityId = 55;

    /**
     * {@inheritdoc}
     */
    public function toArray(): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    protected function getIdProperty(): string
    {
        return 'entityId';
    }
}
