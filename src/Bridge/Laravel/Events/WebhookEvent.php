<?php declare(strict_types=1);

namespace EoneoPay\Webhook\Bridge\Laravel\Events;

use EoneoPay\Utils\Repository;
use Illuminate\Queue\SerializesModels;

abstract class WebhookEvent extends Repository
{
    use SerializesModels;
}
