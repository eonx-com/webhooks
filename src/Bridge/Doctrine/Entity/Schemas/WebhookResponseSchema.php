<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Doctrine\Entity\Schemas;

use Doctrine\ORM\Mapping as ORM;
use EoneoPay\Externals\HttpClient\Interfaces\ResponseInterface;
use EoneoPay\Webhooks\Bridge\Doctrine\Entity\WebhookEntityInterface;

/**
 * @method mixed[]|null getResponse()
 * @method int|null getSequence()
 * @method $this setResponse(array $response)
 * @method $this setSequence(int $sequence)
 */
trait WebhookResponseSchema
{
    /**
     * @ORM\Column(type="json")
     *
     * @var mixed[]
     */
    protected $response;

    /**
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    protected $sequence;

    /**
     * @inheritdoc
     */
    public function populate(WebhookEntityInterface $webhook, ResponseInterface $response): void
    {
        $this->response = $response;
        $this->sequence = $webhook->getSequence();
    }
}
