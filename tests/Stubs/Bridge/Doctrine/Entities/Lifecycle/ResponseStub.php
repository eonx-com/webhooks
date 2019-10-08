<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entities\Lifecycle;

use DateTime;
use EoneoPay\Webhooks\Models\WebhookRequestInterface;
use EoneoPay\Webhooks\Models\WebhookResponseInterface;
use Illuminate\Support\Collection;
use Psr\Http\Message\ResponseInterface;

/**
 * @coversNothing
 */
class ResponseStub implements WebhookResponseInterface
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
     * Constructor.
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
     * Returns data.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getData(): Collection
    {
        return $this->data;
    }

    /**
     * Gets the error string if one is available.
     *
     * @return string
     */
    public function getErrorReason(): ?string
    {
        $reason = $this->data->get('errorReason');

        return \is_string($reason) ? $reason : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getRequest(): WebhookRequestInterface
    {
        return $this->data->get('request', new RequestStub(1));
    }

    /**
     * {@inheritdoc}
     */
    public function getResponse(): ?string
    {
        $response = $this->data->get('response');

        return \is_string($response) ? $response : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getResponseId(): string
    {
        return '123';
    }

    /**
     * {@inheritdoc}
     */
    public function isSuccessful(): bool
    {
        return $this->data->get('successful', false);
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
