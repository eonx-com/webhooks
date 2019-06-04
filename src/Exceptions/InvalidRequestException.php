<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Exceptions;

use RuntimeException;

class InvalidRequestException extends RuntimeException implements WebhooksException
{
}
