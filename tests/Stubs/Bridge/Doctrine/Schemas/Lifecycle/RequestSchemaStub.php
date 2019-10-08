<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Schemas\Lifecycle;

use EoneoPay\Externals\ORM\Entity;
use EoneoPay\Webhooks\Bridge\Doctrine\Schemas\Lifecycle\RequestSchema;

/**
 * @coversNothing
 */
class RequestSchemaStub extends Entity
{
    use RequestSchema;

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
