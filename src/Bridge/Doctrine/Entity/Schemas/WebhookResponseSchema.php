<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Doctrine\Entity\Schemas;

use Doctrine\ORM\Mapping as ORM;
use EoneoPay\Externals\HttpClient\Interfaces\ResponseInterface;
use EoneoPay\Webhooks\Bridge\Doctrine\Entity\WebhookRequestInterface;

/**
 * @method ResponseInterface|null getResponse()
 * @method string|null getResponseId()
 * @method int|null getSequence()
 * @method $this setResponse(array $response)
 * @method $this setResponseId(string $uuid)
 * @method $this setSequence(int $sequence)
 */
trait WebhookResponseSchema
{
    /**
     * @ORM\Column(type="json")
     *
     * @var \EoneoPay\Externals\HttpClient\Interfaces\ResponseInterface
     */
    protected $response;

    /**
     * @ORM\Column(type="guid", name="id")
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Id()
     *
     * @var string
     */
    protected $responseId;

    /**
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    protected $sequence;

    /**
     * {@inheritdoc}
     */
    public function populate(WebhookRequestInterface $webhookRequest, ResponseInterface $response): void
    {
        $this->response = $response;
        $this->sequence = (int)$webhookRequest->getSequence();
    }
}
