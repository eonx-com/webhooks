<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs\Vendor\Doctrine\ORM;

use EoneoPay\Externals\ORM\Interfaces\EntityInterface;
use EoneoPay\Externals\ORM\Interfaces\EntityManagerInterface;
use EoneoPay\Externals\ORM\Interfaces\Query\FilterCollectionInterface;

/**
 * @coversNothing
 */
class ExternalEntityManagerStub implements EntityManagerInterface
{
    /**
     * What is returned by findByIds.
     *
     * @var object[][]
     */
    private $findByIds = [];

    /**
     * Repositories loaded via constructor
     *
     * @var \EoneoPay\Externals\ORM\Interfaces\RepositoryInterface[]
     */
    private $repositories;

    /**
     * Create stub with loaded repositories.
     *
     * @param \EoneoPay\Externals\ORM\Interfaces\RepositoryInterface[]|null $repositories Repositories to load
     */
    public function __construct(?array $repositories = null)
    {
        $this->repositories = $repositories ?? [];
    }

    /**
     * {@inheritdoc}
     */
    public function findByIds(string $class, array $ids): array
    {
        return \array_shift($this->findByIds) ?? [];
    }

    /**
     * {@inheritdoc}
     */
    public function flush(): void
    {
        // TODO: Implement flush() method.
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters(): FilterCollectionInterface
    {
        return new FilterCollectionStub();
    }

    /**
     * {@inheritdoc}
     */
    public function getRepository(string $class)
    {
        return $this->repositories[$class] ?? new RepositoryStub();
    }

    /**
     * {@inheritdoc}
     */
    public function merge(EntityInterface $entity): void
    {
        // TODO: Implement merge() method.
    }

    /**
     * {@inheritdoc}
     */
    public function persist(EntityInterface $entity): void
    {
        // TODO: Implement persist() method.
    }

    /**
     * {@inheritdoc}
     */
    public function remove(EntityInterface $entity): void
    {
        // TODO: Implement remove() method.
    }
}
