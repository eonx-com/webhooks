<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Doctrine\Repositories\Lifecycle;

use DateTime;
use EoneoPay\Webhooks\Bridge\Doctrine\Repositories\FillableRepository;
use EoneoPay\Webhooks\Bridge\Doctrine\Repositories\Interfaces\WebhookRequestRepositoryInterface;
use EoneoPay\Webhooks\Models\ActivityInterface;
use EoneoPay\Webhooks\Models\WebhookRequestInterface;
use EoneoPay\Webhooks\Models\WebhookResponseInterface;

final class RequestRepository extends FillableRepository implements WebhookRequestRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getFailedRequestIds(DateTime $since): iterable
    {
        $buildResponse = $this->entityManager
            ->createQueryBuilder()
            ->from(WebhookResponseInterface::class, 's');

        $passedRequests = $buildResponse->select('DISTINCT(s.request)')
            ->where($buildResponse->expr()->eq('s.statusCode', ':statusCode'))
            ->getDQL();

        $buildRequest = $this->entityManager
            ->createQueryBuilder()
            ->from(WebhookRequestInterface::class, 'q');

        $buildRequest
            ->select('q.requestId')
            ->where($buildRequest->expr()->notIn('q.requestId', $passedRequests))
            ->andWhere($buildRequest->expr()->gte('q.createdAt', ':createdAt'));

        $buildRequest->setParameters([
            'statusCode' => 200,
            'createdAt' => $since->format('Y-m-d H:i:s'),
        ]);

        foreach ($buildRequest->getQuery()->iterate() as $key => $request) {
            /**
             * Doctrine iterator increments index in the result value, this is other way around
             * when the iterator result is an object of entity in which case its always at
             * 0th index. But with fetching just one column in query the result is formatted as.
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
    public function getLatestActivity(string $primaryClass, string $primaryId): ?ActivityInterface
    {
        // Get latest request sequence number for an activity with given primary class and primary id
        $sequence = $this->getLatestSequence($primaryClass, $primaryId);

        if ($sequence === null) {
            return null;
        }

        $buildRequest = $this->entityManager->createQueryBuilder();
        $buildRequest
            ->select('q')
            ->from(WebhookRequestInterface::class, 'q')
            ->join('q.activity', 'a')
            ->where($buildRequest->expr()->eq('q.requestId', ':sequence'))
            ->andWhere($buildRequest->expr()->eq('a.primaryClass', ':primaryClass'))
            ->andWhere($buildRequest->expr()->eq('a.primaryId', ':primaryId'))
            ->setParameters(\compact('sequence', 'primaryClass', 'primaryId'));

        $result = $buildRequest->getQuery()->getOneOrNullResult();

        return ($result instanceof WebhookRequestInterface) === true ? $result->getActivity() : null;
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
            ->from(WebhookRequestInterface::class, 'q');

        $buildRequest
            ->join('q.activity', 'a')
            ->where($buildRequest->expr()->eq('a.primaryClass', ':primaryClass'))
            ->andWhere($buildRequest->expr()->eq('a.primaryId', ':primaryId'));

        // set parameters
        $buildRequest->setParameters(\compact('primaryClass', 'primaryId'));

        $sequence = $buildRequest->getQuery()->getSingleScalarResult();

        return $sequence !== null ? (int)$sequence : null;
    }
}
