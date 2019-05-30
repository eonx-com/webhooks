<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs\Activity;

use EoneoPay\Webhooks\Activities\Interfaces\ActivityDataInterface;

class ActivityDataStub implements ActivityDataInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getActivityConstant(): string
    {
        return 'activity.constant';
    }
}
