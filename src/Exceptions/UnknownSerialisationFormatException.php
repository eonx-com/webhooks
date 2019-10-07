<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Exceptions;

class UnknownSerialisationFormatException extends \RuntimeException implements WebhooksException
{
    /**
     * Create exception.
     *
     * @param string $format
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(string $format, int $code = 0, ?\Throwable $previous = null)
    {
        $message = \sprintf('Unknown serialisation format "%s"', $format);

        parent::__construct($message, $code, $previous);
    }
}
