<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Doctrine\Repositories\Lifecycle;

use EoneoPay\Webhooks\Bridge\Doctrine\Repositories\FillableRepository;
use EoneoPay\Webhooks\Bridge\Doctrine\Repositories\Interfaces\WebhookResponseRepositoryInterface;

class ResponseRepository extends FillableRepository implements WebhookResponseRepositoryInterface
{
}
