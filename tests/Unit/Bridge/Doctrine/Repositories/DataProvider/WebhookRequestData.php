<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Bridge\Doctrine\Repositories\DataProvider;

use DateTime as BaseDateTime;
use Doctrine\ORM\EntityManagerInterface;
use EoneoPay\Webhooks\Models\ActivityInterface;
use Tests\EoneoPay\Webhooks\TestCases\Traits\ModelFactoryTrait;

/**
 * @coversNothing
 */
class WebhookRequestData
{
    use ModelFactoryTrait;

    /**
     * @var \EoneoPay\Webhooks\Models\ActivityInterface|null
     */
    private $activity;

    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $entityManager;

    /**
     * Last known response id generated.
     *
     * @var int
     */
    private $lastResponseId = 0;

    /**
     * @var \EoneoPay\Webhooks\Models\WebhookRequestInterface[]|null
     */
    private $requests;

    /**
     * WebhookRequestData constructor.
     *
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     * @param \EoneoPay\Webhooks\Models\ActivityInterface|null $activity
     */
    public function __construct(EntityManagerInterface $entityManager, ?ActivityInterface $activity = null)
    {
        $this->entityManager = $entityManager;
        $this->activity = $activity;
    }

    /**
     * Build the data.
     *
     * @return \Tests\EoneoPay\Webhooks\Unit\Bridge\Doctrine\Repositories\DataProvider\WebhookRequestData
     */
    public function build(): self
    {
        $this->entityManager->flush();

        return $this;
    }

    /**
     * Create a webhook request entity.
     *
     * @param \DateTime $createdAt
     * @param int $requestId
     *
     * @return \Tests\EoneoPay\Webhooks\Unit\Bridge\Doctrine\Repositories\DataProvider\WebhookRequestData
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException
     * @throws \ReflectionException
     */
    public function createRequest(BaseDateTime $createdAt, int $requestId): self
    {
        if ($this->activity === null) {
            $this->activity = $this->getActivityEntity();
            $this->entityManager->persist($this->activity);
        }

        $entity = $this->getRequestEntity(
            $this->activity,
            $createdAt,
            $requestId
        );

        $this->entityManager->persist($entity);
        $this->requests[$requestId] = $entity;

        return $this;
    }

    /**
     * Create a webhook response entity.
     *
     * @param int $requestId
     * @param int $statusCode
     *
     * @return \Tests\EoneoPay\Webhooks\Unit\Bridge\Doctrine\Repositories\DataProvider\WebhookRequestData
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException
     * @throws \ReflectionException
     */
    public function createResponse(int $requestId, int $statusCode): self
    {
        $responseId = $this->lastResponseId++;
        $request = $this->requests[$requestId] ?? $this->getRequestEntity();
        $entity = $this->getResponseEntity($request, (string)$responseId, $statusCode);

        $this->entityManager->persist($entity);

        return $this;
    }
}
