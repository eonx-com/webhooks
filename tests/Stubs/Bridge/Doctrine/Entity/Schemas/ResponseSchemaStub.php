<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entity\Schemas;

use EoneoPay\Externals\ORM\Entity;
use EoneoPay\Webhooks\Bridge\Doctrine\Entities\Schemas\WebhookResponseSchema;

/**
 * @coversNothing
 */
class ResponseSchemaStub extends Entity
{
    use WebhookResponseSchema;

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
