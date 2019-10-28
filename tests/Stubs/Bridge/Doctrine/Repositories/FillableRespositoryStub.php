<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Repositories;

use ArrayIterator;
use EoneoPay\Webhooks\Bridge\Doctrine\Repositories\Interfaces\FillableRepositoryInterface;
use Tests\EoneoPay\Webhooks\Stubs\Vendor\Doctrine\ORM\RepositoryStub;

/**
 * @coversNothing
 */
class FillableRespositoryStub extends RepositoryStub implements FillableRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getFillIterable(): iterable
    {
        return new ArrayIterator($this->findAll());
    }
}
