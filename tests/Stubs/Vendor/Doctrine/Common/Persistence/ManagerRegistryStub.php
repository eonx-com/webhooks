<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs\Vendor\Doctrine\Common\Persistence;

use Doctrine\Common\Persistence\ManagerRegistry;
use Tests\EoneoPay\Webhooks\Stubs\Vendor\Doctrine\ORM\EntityManagerStub;

class ManagerRegistryStub implements ManagerRegistry
{
    /**
     * {@inheritdoc}
     */
    public function getAliasNamespace($alias)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getConnection($name = null)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getConnectionNames()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getConnections()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultConnectionName()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultManagerName()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getManager($name = null)
    {
        return new EntityManagerStub();
    }

    /**
     * {@inheritdoc}
     */
    public function getManagerForClass($class)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getManagerNames()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getManagers()
    {
    }

    /**
     * {@inheritdoc}
     *
     * @SuppressWarnings(PHPMD.LongVariable) Variable name dictated by interface
     */
    public function getRepository($persistentObject, $persistentManagerName = null)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function resetManager($name = null)
    {
    }
}
