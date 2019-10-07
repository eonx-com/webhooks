<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Laravel\Events;

final class WebhookRequestRetryEvent
{
    /**
     * @var int
     */
    private $requestId;

    /**
     * Constructor.
     *
     * @param int $requestId
     */
    public function __construct(int $requestId)
    {
        $this->requestId = $requestId;
    }

    /**
     * Returns request id.
     *
     * @return int
     */
    public function getRequestId(): int
    {
        return $this->requestId;
    }
}
