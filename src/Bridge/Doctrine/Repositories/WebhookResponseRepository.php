<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Doctrine\Repositories;

use EoneoPay\Externals\ORM\Repository;

class WebhookResponseRepository extends Repository
{
    /**
     * Get list of webhook requests that have passed
     *
     * @return array
     */
    public function getPassedRequests(): array
    {
        $buildPassed = $this->createQueryBuilder('q');
    }
}