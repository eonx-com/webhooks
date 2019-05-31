<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Model;

use Psr\Http\Message\ResponseInterface;

interface WebhookResponseInterface
{
    /**
     * If the response was successful
     *
     * @return bool
     */
    public function isSuccessful(): bool;

    /**
     * Populates the WebhookResponse with data.
     *
     * @param \EoneoPay\Webhooks\Model\WebhookRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param string $truncatedRequest
     *
     * @return void
     */
    public function populateResponse(
        WebhookRequestInterface $request,
        ResponseInterface $response,
        string $truncatedRequest
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
