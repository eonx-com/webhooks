<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entity;

use DateTime;
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
     * @var \DateTime
     */
    private $createdAt;

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
     * {@inheritdoc}
     */
    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
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
    public function getRequest(): WebhookRequestInterface
    {
        return $this->data['request'] ?? new WebhookRequestStub(1);
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
    public function populate(
        WebhookRequestInterface $request,
        ResponseInterface $response,
        string $truncatedResponse
    ): void {
        $this->data['request'] = $request;
        $this->data['response'] = $response;
        $this->data['truncatedResponse'] = $truncatedResponse;
    }

    /**
     * {@inheritdoc}
     */
    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
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
