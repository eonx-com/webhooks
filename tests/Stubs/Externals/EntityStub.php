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
        return 'id';
    }
}
