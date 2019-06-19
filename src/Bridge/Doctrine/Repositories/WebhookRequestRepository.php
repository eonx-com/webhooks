<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Doctrine\Repositories;

use DateTime;
use Doctrine\ORM\Query;
use EoneoPay\Externals\ORM\Repository;
use EoneoPay\Webhooks\Bridge\Doctrine\Entities\WebhookRequest;
use EoneoPay\Webhooks\Bridge\Doctrine\Entities\WebhookResponse;

class WebhookRequestRepository extends Repository
{
    /**
     * Get list of webhook requests that have failed since provided date time
     *
     * @param \DateTime $since Datetime since there have been failed webhook requests
     *
     * @return \Doctrine\ORM\Query
     */
    public function getFailedRequests(DateTime $since): Query
    {
        $buildResponse = $this->entityManager
            ->createQueryBuilder()
            ->from(WebhookResponse::class, 's');

        $buildRequest = $this->entityManager
            ->createQueryBuilder()
            ->from(WebhookRequest::class, 'q');

        $buildRequest
            ->select('q.requestId')
            ->where($buildRequest->expr()->notIn('q.requestId', ':requestIdNotIn'))
            ->andWhere($buildRequest->expr()->gte('q.createdAt', ':createdAt'));

        $buildRequest->setParameters([
            'requestIdNotIn' => $buildResponse->select('DISTINCT(s.request)')
                ->where($buildResponse->expr()->eq('s.statusCode', ':statusCode'))
                ->setParameters([
                    'statusCode' => 200
                ])->getDQL(),
            'createdAt' => $since->format('Y:m:d H:i:s')
        ]);

        return $buildRequest->getQuery();

    }
}
