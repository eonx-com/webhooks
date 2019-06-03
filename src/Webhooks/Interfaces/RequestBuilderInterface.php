<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Webhooks\Interfaces;

use EoneoPay\Webhooks\Model\WebhookRequestInterface;
use Psr\Http\Message\RequestInterface;

interface RequestBuilderInterface
{
    /**
     * Builds a RequestInterface to be sent.
     *
     * @param \EoneoPay\Webhooks\Model\WebhookRequestInterface $webhookRequest
     *
     * @return \Psr\Http\Message\RequestInterface
     */
    public function build(WebhookRequestInterface $webhookRequest): RequestInterface;
}
