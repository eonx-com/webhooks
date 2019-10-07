<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs\Webhooks;

use EoneoPay\Webhooks\Models\WebhookRequestInterface;
use EoneoPay\Webhooks\Webhooks\Interfaces\RequestBuilderInterface;
use Psr\Http\Message\RequestInterface;
use Zend\Diactoros\Request;

/**
 * @coversNothing
 */
class RequestBuilderStub implements RequestBuilderInterface
{
    /**
     * {@inheritdoc}
     */
    public function build(WebhookRequestInterface $webhookRequest): RequestInterface
    {
        return new Request();
    }
}
