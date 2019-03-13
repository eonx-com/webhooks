<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Doctrine\Entity\Schemas;

use Doctrine\ORM\Mapping as ORM;
use EoneoPay\Externals\HttpClient\Interfaces\ResponseInterface;

/**
 * @method mixed[]|null getResponse()
 * @method $this setResponse(array $response)
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
     * @inheritdoc
     */
    public function populate(int $sequence, ResponseInterface $response): void
    {
        $this->response = $response;
    }
}
