<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Activities\Interfaces;

use EoneoPay\Externals\ORM\Interfaces\EntityInterface;

interface ActivityDataInterface
{
    /**
     * Returns the activity key.
     *
     * @return string
     */
    public static function getActivityKey(): string;

    /**
     * Returns the primary entity that this activity is about.
     *
     * @return \EoneoPay\Externals\ORM\Interfaces\EntityInterface
     */
    public function getPrimaryEntity(): EntityInterface;
}
