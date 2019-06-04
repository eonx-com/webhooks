<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Model;

use Psr\Http\Message\ResponseInterface;

interface WebhookResponseInterface
{
    /**
     * Returns the request that the response was in response to.
     *
     * @return \EoneoPay\Webhooks\Model\WebhookRequestInterface
     */
    public function getRequest(): WebhookRequestInterface;

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
