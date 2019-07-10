<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Model;

use DateTime;
use Psr\Http\Message\ResponseInterface;

interface WebhookResponseInterface
{
    /**
     * Returns the date time when this response was created
     *
     * @return \DateTime|null
     */
    public function getCreatedAt(): ?DateTime;

    /**
     * Gets the error string if one is available.
     *
     * @return string
     */
    public function getErrorReason(): ?string;

    /**
     * Returns the request that the response was in response to.
     *
     * @return \EoneoPay\Webhooks\Model\WebhookRequestInterface
     */
    public function getRequest(): WebhookRequestInterface;

    /**
     * Returns the identifier of the response.
     *
     * @return string
     */
    public function getResponseId(): string;

    /**
     * If the webhook response is considered successful. This is most
     * likely to mean we got a 2xx response.
     *
     * @return bool
     */
    public function isSuccessful(): bool;

    /**
     * Populates the WebhookResponse with data.
     *
     * @param \EoneoPay\Webhooks\Model\WebhookRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param string $truncatedResponse
     *
     * @return void
     */
    public function populate(
        WebhookRequestInterface $request,
        ResponseInterface $response,
        string $truncatedResponse
    ): void;

    /**
     * Set created at date on the webhook response
     *
     * @param \DateTime $createdAt
     *
     * @return void
     */
    public function setCreatedAt(DateTime $createdAt): void;

    /**
     * Sets an error string that caused an error if one is available.
     *
     * @param string $message
     *
     * @return void
     */
    public function setErrorReason(string $message): void;

    /**
     * Sets the the response should be considered successful.
     *
     * @param bool $successful
     *
     * @return void
     */
    public function setSuccessful(bool $successful): void;
}
