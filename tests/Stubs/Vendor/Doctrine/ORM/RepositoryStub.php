<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs\Vendor\Doctrine\ORM;

use EoneoPay\Externals\ORM\Interfaces\RepositoryInterface;
use EoneoPay\Webhooks\Bridge\Doctrine\Entities\Entity;

/**
 * @coversNothing
 */
class RepositoryStub implements RepositoryInterface
{
    /**
     * Entities.
     *
     * @var \EoneoPay\Webhooks\Bridge\Doctrine\Entities\Entity[]
     */
    private $entities;

    /**
     * Create repository stub.
     *
     * @param \EoneoPay\Webhooks\Bridge\Doctrine\Entities\Entity[]|null $entities Entities to load into repository
     */
    public function __construct(?array $entities = null)
    {
        $this->entities = $entities ?? [];
    }

    /**
     * {@inheritdoc}
     */
    public function count(?array $criteria = null): int
    {
        return 0;
    }

    /**
     * {@inheritdoc}
     *
     * @SuppressWarnings(PHPMD.ShortVariable) Parameter is inherited from interface
     */
    public function find($id)
    {
        $entity = \reset($this->entities);

        return ($entity instanceof Entity) === true ? $entity : null;
    }

    /**
     * {@inheritdoc}
     */
    public function findAll(): array
    {
        return $this->entities ?? [];
    }

    /**
     * NOTE: This is the limit of the generic stub. If you need more complex behaviour, you should write a custom stub.
     *
     * {@inheritdoc}
     */
    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null): array
    {
        $result = [];
        foreach ($this->entities as $entity) {
            $matched = true;
            foreach ($criteria as $key => $value) {
                if ($value === null) {
                    continue;
                }

                $method = \sprintf('get%s', \ucfirst($key));
                $entityValue = $entity->{$method}();

                if ($entityValue !== $value) {
                    $matched = false;
                }
            }

            if ($matched === true) {
                $result[] = $entity;
            }
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function findOneBy(array $criteria, ?array $orderBy = null)
    {
        $response = $this->findBy($criteria);

        return \array_pop($response);
    }

    /**
     * {@inheritdoc}
     */
    public function getClassName(): string
    {
        return __CLASS__;
    }
}
