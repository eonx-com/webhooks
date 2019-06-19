<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Doctrine\Entities;

use DateTime as BaseDateTime;
use EoneoPay\Externals\ORM\Entity as BaseEntity;
use EoneoPay\Utils\Interfaces\UtcDateTimeInterface;

abstract class Entity extends BaseEntity
{
    /**
     * Format a date/time into zulu format
     *
     * @param \DateTime|null $datetime The datetime object to format
     * @param string|null $format How to format the date
     *
     * @return string|null
     */
    public function formatDate(?BaseDateTime $datetime, ?string $format = null): ?string
    {
        return $datetime === null ? null : $datetime->format($format ?? UtcDateTimeInterface::FORMAT_ZULU);
    }
}
