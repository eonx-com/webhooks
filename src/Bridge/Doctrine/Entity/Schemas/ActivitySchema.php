<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Doctrine\Entity\Schemas;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use EoneoPay\Externals\ORM\Interfaces\EntityInterface;

/**
 * @method int|null getActivityId()
 * @method string|null getActivityKey()
 * @method \DateTime|null getOccurredAt()
 * @method mixed[]|null getPayload()
 * @method string|null getPrimaryClass()
 * @method string|null getPrimaryId()
 * @method $this setActivityId(int $id)
 * @method $this setActivityKey(string $activityKey)
 * @method $this setOccurredAt(\DateTime $occurredAt)
 * @method $this setPayload(array $payload)
 * @method $this setPrimaryClass(string $class)
 * @method $this setPrimaryId(string $id)
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
    protected $activityKey;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var DateTime
     */
    protected $occurredAt;

    /**
     * @ORM\Column(type="json")
     *
     * @var mixed[]
     */
    protected $payload;

    /**
     * The full FQCN of the primary object.
     *
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $primaryClass;

    /**
     * The identifier of the primary object.
     *
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $primaryId;

    /**
     * Sets the primary Entity that caused this event.
     *
     * @param \EoneoPay\Externals\ORM\Interfaces\EntityInterface $object
     *
     * @return void
     */
    public function setPrimaryEntity(EntityInterface $object): void
    {
        $this->primaryClass = \get_class($object);
        $this->primaryId = (string)$object->getId();
    }
}
