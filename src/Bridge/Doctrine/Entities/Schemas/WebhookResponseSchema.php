<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Doctrine\Entities\Schemas;

use Doctrine\ORM\Mapping as ORM;
use EoneoPay\Webhooks\Model\WebhookRequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @method string|null getErrorReason()
 * @method string|null getResponse()
 * @method int|null getResponseId()
 * @method int|null getStatusCode()
 * @method bool isSuccessful()
 * @method $this setErrorReason(string $errorReason)
 * @method $this setResponse(string $response)
 * @method $this setResponseId(int $id)
 * @method $this setStatusCode(int $status)
 * @method $this setSuccessful(bool $successful)
 */
trait WebhookResponseSchema
{
    /**
     * Stores an error reason if an exception occurred while trying to process
     * a webhook request.
     *
     * @ORM\Column(type="text", nullable=true)
     *
     * @var string|null
     */
    protected $errorReason;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @var string|null
     */
    protected $response;

    /**
     * @ORM\Column(type="bigint", name="id")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Id()
     *
     * @var string
     */
    protected $responseId;

    /**
     * The status code of the response if one was returned.
     *
     * @ORM\Column(type="integer", nullable=true)
     *
     * @var int|null
     */
    protected $statusCode;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    protected $successful = false;

    /**
     * {@inheritdoc}
     */
    public function populate(
        WebhookRequestInterface $request,
        ResponseInterface $response,
        string $truncatedResponse
    ): void {
        $this->response = $truncatedResponse;
        $this->statusCode = $response->getStatusCode();
    }
}
