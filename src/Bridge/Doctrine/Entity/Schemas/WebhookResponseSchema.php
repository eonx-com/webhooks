<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Doctrine\Entity\Schemas;

use Doctrine\ORM\Mapping as ORM;
use EoneoPay\Webhooks\Model\WebhookRequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @method string|null getResponse()
 * @method string|null getResponseId()
 * @method int|null getSequence()
 * @method $this setResponse(string $response)
 * @method $this setResponseId(string $uuid)
 * @method $this setSequence(int $sequence)
 */
trait WebhookResponseSchema
{
    /**
     * @ORM\Column(type="text")
     *
     * @var string
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
    public function populateRequest(
        WebhookRequestInterface $request,
        ResponseInterface $response,
        string $truncatedRequest
    ): void {
        $this->response = $truncatedRequest;
        $this->sequence = (int)$request->getSequence();
    }
}
