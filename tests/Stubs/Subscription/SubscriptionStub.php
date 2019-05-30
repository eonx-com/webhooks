<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs\Subscription;

use EoneoPay\Webhooks\Subscription\Interfaces\SubscriptionInterface;

/**
 * @coversNothing
 */
class SubscriptionStub implements SubscriptionInterface
{
    /**
     * @var string
     */
    private $format;

    /**
     * Constructor
     *
     * @param null|string $format
     */
    public function __construct(?string $format = null)
    {
        $this->format = $format ?? 'json';
    }

    /**
     * {@inheritdoc}
     */
    public function getHeaders(): array
    {
        return ['authorization' => 'Bearer ABC123'];
    }

    /**
     * {@inheritdoc}
     */
    public function getMethod(): string
    {
        return 'POST';
    }

    /**
     * {@inheritdoc}
     */
    public function getSerializationFormat(): string
    {
        return $this->format;
    }

    /**
     * {@inheritdoc}
     */
    public function getUrl(): string
    {
        return 'https://127.0.0.1/webhook';
    }
}
