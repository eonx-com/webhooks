<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Doctrine\Repositories\Interfaces;

interface FillableRepositoryInterface
{
    /**
     * Returns an iterable that is used to fill search indicies with the entity
     * that the repository belongs to.
     *
     * @return \EoneoPay\Webhooks\Bridge\Doctrine\Entities\Entity[]|\Traversable
     */
    public function getFillIterable(): iterable;
}
