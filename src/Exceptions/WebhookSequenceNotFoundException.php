<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Exceptions;

use RuntimeException;

class WebhookSequenceNotFoundException extends RuntimeException implements WebhooksException
{
}
