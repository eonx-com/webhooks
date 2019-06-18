<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Webhooks;

use EoneoPay\Webhooks\Webhooks\Interfaces\RequestProcessorInterface;

class RetryProcessor
{
    /**
     * @var \EoneoPay\Webhooks\Webhooks\Interfaces\RequestProcessorInterface
     */
    private $requestProcessor;

    /**
     * RetryProcessor constructor.
     *
     * @param \EoneoPay\Webhooks\Webhooks\Interfaces\RequestProcessorInterface $requestProcessor
     */
    public function __construct(RequestProcessorInterface $requestProcessor)
    {
        $this->requestProcessor = $requestProcessor;
    }

    public function retry(): void
    {

    }
}