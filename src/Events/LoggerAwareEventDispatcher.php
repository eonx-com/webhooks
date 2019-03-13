<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Events;

use EoneoPay\Externals\Logger\Interfaces\LoggerInterface;
use EoneoPay\Webhooks\Events\Interfaces\EventInterface;
use EoneoPay\Webhooks\Events\Interfaces\WebhookEventDispatcherInterface;

final class LoggerAwareEventDispatcher implements WebhookEventDispatcherInterface
{
    /**
     * @var \EoneoPay\Webhooks\Events\Interfaces\WebhookEventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var \EoneoPay\Externals\Logger\Interfaces\LoggerInterface
     */
    private $logger;

    /**
     * Constructor
     *
     * @param \EoneoPay\Webhooks\Events\Interfaces\WebhookEventDispatcherInterface $dispatcher
     * @param \EoneoPay\Externals\Logger\Interfaces\LoggerInterface $logger
     */
    public function __construct(
        WebhookEventDispatcherInterface $dispatcher,
        LoggerInterface $logger
    ) {
        $this->dispatcher = $dispatcher;
        $this->logger = $logger;
    }

    /**
     * This class will log each webhook to the supplied logger.
     *
     * @inheritdoc
     */
    public function dispatch(EventInterface $event): void
    {
        $this->logger->info('Dispatching Webhook', [
            'format' => $event->getFormat(),
            'headers' => $this->sanitiseHeaders($event->getHeaders()),
            'method' => $event->getMethod(),
            'payload' => $event->getPayload(),
            'sequence' => $event->getSequence(),
            'url' => $event->getUrl()
        ]);

        $this->dispatcher->dispatch($event);
    }

    /**
     * Remove sensitive headers
     *
     * @param string[] $headers
     *
     * @return string[]
     */
    private function sanitiseHeaders(array $headers): array
    {
        if (\array_key_exists('Authorization', $headers)) {
            $headers['Authorization'] = 'REDACTED';
        }

        if (\array_key_exists('authorization', $headers)) {
            $headers['authorization'] = 'REDACTED';
        }

        return $headers;
    }
}
