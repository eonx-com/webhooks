<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Doctrine\Exceptions;

use EoneoPay\Webhooks\Exceptions\WebhooksException;
use RuntimeException;

class UnexpectedObjectException extends RuntimeException implements WebhooksException
{
}
