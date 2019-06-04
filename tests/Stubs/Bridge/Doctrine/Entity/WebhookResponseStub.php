<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entity;

use EoneoPay\Webhooks\Model\WebhookRequestInterface;
use EoneoPay\Webhooks\Model\WebhookResponseInterface;
use Illuminate\Support\Collection;
use Psr\Http\Message\ResponseInterface;

/**
 * @coversNothing
 */
class WebhookResponseStub implements WebhookResponseInterface
{
    /**
     * @var \Illuminate\Support\Collection
     */
    private $data;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->data = new Collection();
    }

    /**
     * Returns data
     *
     * @return \Illuminate\Support\Collection
     */
    public function getData(): Collection
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     */
    public function isSuccessful(): bool
    {
        return $this->data['successful'] ?? false;
    }

    /**
     * {@inheritdoc}
     */
    public function populateResponse(
        WebhookRequestInterface $request,
        ResponseInterface $response,
        string $truncatedRequest
    ): void {
        $this->data['request'] = $request;
        $this->data['response'] = $response;
        $this->data['truncatedResponse'] = $truncatedRequest;
    }

    /**
     * {@inheritdoc}
     */
    public function setErrorReason(string $reason): void
    {
        $this->data['errorReason'] = $reason;
    }

    /**
     * {@inheritdoc}
     */
    public function setSuccessful(bool $successful): void
    {
        $this->data['successful'] = $successful;
    }
}
