<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Activity\Interfaces;

interface ActivityDataInterface
{
    /**
     * Returns the webhook event constant.
     *
     * @return string
     */
    public static function getActivityConstant(): string;
}
