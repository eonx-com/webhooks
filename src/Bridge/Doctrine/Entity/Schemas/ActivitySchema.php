<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Doctrine\Entity\Schemas;

/**
 * @method int|null getActivityId()
 * @method string|null getEvent()
 * @method mixed[]|null getPayload()
 * @method $this setActivityId(int $id)
 * @method $this setEvent(string $event)
 * @method $this setPayload(array $payload)
 */
trait ActivitySchema
{
    /**
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Id()
     *
     * @var int
     */
    protected $activityId;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string|null
     */
    protected $event;

    /**
     * @ORM\Column(type="json")
     *
     * @var mixed[]
     */
    protected $payload;
}
