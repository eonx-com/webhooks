<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs\Activity;

use EoneoPay\Externals\ORM\Interfaces\EntityInterface;
use EoneoPay\Webhooks\Activities\Interfaces\ActivityDataInterface;
use Tests\EoneoPay\Webhooks\Stubs\Externals\EntityStub;

/**
 * @coversNothing
 */
class ActivityDataStub implements ActivityDataInterface
{
    /**
     * @var \EoneoPay\Externals\ORM\Interfaces\EntityInterface
     */
    private $entity;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->entity = new EntityStub();
    }

    /**
     * {@inheritdoc}
     */
    public static function getActivityKey(): string
    {
        return 'activity.constant';
    }

    /**
     * {@inheritdoc}
     */
    public function getPrimaryEntity(): EntityInterface
    {
        return $this->entity;
    }
}
