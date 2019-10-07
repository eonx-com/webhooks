<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entities;

use EoneoPay\Webhooks\Bridge\Doctrine\Entities\Entity;

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
        return '';
    }
}
