<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Doctrine\Repositories;

use Doctrine\ORM\Query;
use EoneoPay\Externals\ORM\Repository;
use EoneoPay\Webhooks\Bridge\Doctrine\Entities\WebhookResponse;

class WebhookRequestRepository extends Repository
{
    /**
     * Get list of webhook requests that have passed
     *
     * @return \Doctrine\ORM\Query
     */
    public function getPassedRequests(): Query
    {
        $buildResponse = $this->entityManager
            ->createQueryBuilder()
            ->from(WebhookResponse::class, 's');

        $buildResponse->select('DISTINCT(s.request)')
            ->where($buildResponse->expr()->eq('s.statusCode', ':statusCode'));

        $buildResponse->setParameters([
            'statusCode' => 200
        ]);

        $requestPassed = $buildResponse->getQuery()->getResult();

        $buildRequest = $this->createQueryBuilder('q');

        $buildRequest
            ->where(
                $buildRequest->expr()->notIn('q.requestId', ':requestIdNotIn')
            );

        $buildRequest->setParameters([
            'requestIdNotIn' => $requestPassed
        ]);

        $query = $buildRequest->getQuery();
        $requests = $query->getResult();

        return $query;
    }
}
