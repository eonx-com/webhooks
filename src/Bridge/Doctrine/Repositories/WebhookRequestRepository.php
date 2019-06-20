<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Doctrine\Repositories;

use DateTime;
use EoneoPay\Externals\ORM\Repository;
use EoneoPay\Webhooks\Bridge\Doctrine\Entities\WebhookRequest;
use EoneoPay\Webhooks\Bridge\Doctrine\Entities\WebhookResponse;
use EoneoPay\Webhooks\Bridge\Doctrine\Repositories\Interfaces\WebhookRequestRepositoryInterface;

class WebhookRequestRepository extends Repository implements WebhookRequestRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getFailedRequestIds(DateTime $since): iterable
    {
        $buildResponse = $this->entityManager
            ->createQueryBuilder()
            ->from(WebhookResponse::class, 's');

        $passedRequests = $buildResponse->select('DISTINCT(s.request)')
            ->where($buildResponse->expr()->eq('s.statusCode', ':statusCode'))
            ->getDQL();

        $buildRequest = $this->entityManager
            ->createQueryBuilder()
            ->from(WebhookRequest::class, 'q');

        $buildRequest
            ->select('q.requestId')
            ->where($buildRequest->expr()->notIn('q.requestId', $passedRequests))
            ->andWhere($buildRequest->expr()->gte('q.createdAt', ':createdAt'));

        $buildRequest->setParameters([
            'statusCode' => 200,
            'createdAt' => $since->format('Y-m-d H:i:s')
        ]);

        foreach ($buildRequest->getQuery()->iterate() as $key => $request) {
            yield $request[$key];
        }
    }
}
