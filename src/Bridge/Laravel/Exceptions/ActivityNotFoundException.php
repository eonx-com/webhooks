<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Laravel\Exceptions;

use EoneoPay\Webhooks\Exceptions\WebhooksException;
use RuntimeException;

class ActivityNotFoundException extends RuntimeException implements WebhooksException
{
}
