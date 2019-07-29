<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs\Vendor\Doctrine\ORM;

use EoneoPay\Externals\ORM\Interfaces\Query\FilterCollectionInterface;

/**
 * @coversNothing
 */
final class FilterCollectionStub implements FilterCollectionInterface
{
    /**
     * {@inheritdoc}
     */
    public function disable($name): void
    {
        // TODO: Implement disable() method.
    }

    /**
     * {@inheritdoc}
     */
    public function enable($name): void
    {
        // TODO: Implement enable() method.
    }
}
