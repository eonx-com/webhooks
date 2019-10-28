<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Doctrine\Repositories;

use EoneoPay\Externals\ORM\Repository;
use EoneoPay\Webhooks\Bridge\Doctrine\Repositories\Interfaces\FillableRepositoryInterface;

class FillableRepository extends Repository implements FillableRepositoryInterface
{
    /**
     * {@inheritdoc}
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function getFillIterable(): iterable
    {
        $builder = $this->createQueryBuilder('w');

        foreach ($builder->getQuery()->iterate() as $result) {
            yield $result[0];
        }
    }
}
