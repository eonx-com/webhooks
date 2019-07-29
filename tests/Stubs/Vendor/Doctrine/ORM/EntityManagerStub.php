<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs\Vendor\Doctrine\ORM;

use EoneoPay\Externals\ORM\Interfaces\EntityInterface;
use EoneoPay\Externals\ORM\Interfaces\EntityManagerInterface;
use EoneoPay\Externals\ORM\Interfaces\Query\FilterCollectionInterface;
use EoneoPay\Externals\ORM\Interfaces\RepositoryInterface;

/**
 * @coversNothing
 */
class EntityManagerStub implements EntityManagerInterface
{
    /**
     * What is returned by findByIds.
     *
     * @var object[][]
     */
    private $findByIds = [];

    /**
     * Number of times flush has been called.
     *
     * @var int
     */
    private $flushCount = 0;

    /**
     * Repositories loaded via constructor.
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
     * Adds a findByIds return.
     *
     * @param object[] $findByIds
     *
     * @return void
     */
    public function addFindByIds(array $findByIds): void
    {
        $this->findByIds[] = $findByIds;
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
        $this->flushCount++;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters(): FilterCollectionInterface
    {
        return new FilterCollectionStub();
    }

    /**
     * Gets the number of times the entity manager flush stub has been called.
     *
     * @return int
     */
    public function getFlushCount(): int
    {
        return $this->flushCount;
    }

    /**
     * {@inheritdoc}
     */
    public function getRepository(string $class): RepositoryInterface
    {
        return $this->repositories[$class] ?? new RepositoryStub();
    }

    /**
     * {@inheritdoc}
     */
    public function merge(EntityInterface $entity): void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function persist(EntityInterface $entity): void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function remove(EntityInterface $entity): void
    {
    }
}
