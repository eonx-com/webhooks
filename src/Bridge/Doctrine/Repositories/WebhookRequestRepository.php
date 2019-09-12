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
            /**
             * Doctrine iterator increments index in the result value, this is other way around
             * when the iterator result is an object of entity in which case its always at
             * 0th index. But with fetching just one column in query the result is formatted aso
             *
             * [
             *   0 => [0 => ['requestId' => 10]],
             *   1 => [1 => ['requestId' => 20]]
             * ]
             */
            yield $request[$key];
        }
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getLatestPayload(string $primaryClass, string $primaryId): ?array
    {
        // Get latest request sequence number for an activity with given primary class and primary id
        $sequence = $this->getLatestSequence($primaryClass, $primaryId);

        $buildRequest = $this->entityManager
            ->createQueryBuilder()
            ->select('q')
            ->from(WebhookRequest::class, 'q')
            ->join('q.activity', 'a');

        if ($sequence !== null) {
            $buildRequest->where($buildRequest->expr()->eq('q.requestId', ':sequence'))
                ->setParameter('sequence', $sequence);
        }

        $results = $buildRequest->getQuery()->getResult();

        return (\count($results) > 0) === true ? $results[0]->getActivity()->getPayload() : null;
    }

    /**
     * Get latest sequence id of the activity request made for a provided primary class with given
     * primary id.
     *
     * @param string $primaryClass Primary class the request is associated with
     * @param string $primaryId Id of the provided primary class
     *
     * @return int|null Latest sequence number of the request
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    private function getLatestSequence(string $primaryClass, string $primaryId): ?int
    {
        $buildRequest = $this->entityManager
            ->createQueryBuilder()
            ->select('MAX(q.requestId) as maxSequence')
            ->from(WebhookRequest::class, 'q');

        $buildRequest
            ->join('q.activity', 'a')
            ->where($buildRequest->expr()->eq('a.primaryClass', ':primaryClass'))
            ->andWhere($buildRequest->expr()->eq('a.primaryId', ':primaryId'));

        // set parameters
        $buildRequest->setParameters([
            'primaryClass' => $primaryClass,
            'primaryId' => $primaryId
        ]);

        $sequence = $buildRequest->getQuery()->getSingleScalarResult();

        return $sequence !== null ? (int)$sequence : null;
    }
}
