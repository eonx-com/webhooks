<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Doctrine\Repositories;

use DateTime;
use EoneoPay\Externals\ORM\Repository;
use EoneoPay\Webhooks\Bridge\Doctrine\Entities\WebhookRequest;
use EoneoPay\Webhooks\Bridge\Doctrine\Entities\WebhookResponse;
use EoneoPay\Webhooks\Bridge\Doctrine\Repositories\Interfaces\WebhookRequestRepositoryInterface;
use Iterator;

class WebhookRequestRepository extends Repository implements WebhookRequestRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getFailedRequests(DateTime $since): Iterator
    {
        $buildResponse = $this->entityManager
            ->createQueryBuilder()
            ->from(WebhookResponse::class, 's');

        $passedRequests = $buildResponse->select('DISTINCT(s.request)')
            ->where($buildResponse->expr()->eq('s.statusCode', ':statusCode'))
            ->setParameters([
                'statusCode' => 200
            ])->getDQL();

        $buildRequest = $this->entityManager
            ->createQueryBuilder()
            ->from(WebhookRequest::class, 'q');

        $buildRequest
            ->select('q.requestId')
            ->where($buildRequest->expr()->notIn('q.requestId', ':requestIdNotIn'))
            ->andWhere($buildRequest->expr()->gte('q.createdAt', ':createdAt'));

        $buildRequest->setParameters([
            'requestIdNotIn' => $passedRequests,
            'createdAt' => $since->format('Y:m:d H:i:s')
        ]);

        return $buildRequest->getQuery()->iterate();
    }
}
