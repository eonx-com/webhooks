<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\TestCases\Traits;

use DateTime as BaseDateTime;
use EoneoPay\Utils\DateTime;
use EoneoPay\Webhooks\Bridge\Doctrine\Entities\Activity;
use EoneoPay\Webhooks\Bridge\Doctrine\Entities\WebhookRequest;
use EoneoPay\Webhooks\Bridge\Doctrine\Entities\WebhookResponse;
use EoneoPay\Webhooks\Model\ActivityInterface;
use ReflectionClass;
use Tests\EoneoPay\Webhooks\Stubs\Externals\EntityStub;

/**
 * @covers \Tests\EoneoPay\Webhooks\TestCases\Traits\ModelFactoryTrait
 */
trait ModelFactoryTrait
{
    /**
     * Returns the Activity entity.
     *
     * @param int|null $activityId
     * @param string|null $key
     *
     * @return \EoneoPay\Webhooks\Bridge\Doctrine\Entities\Activity
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException
     * @throws \ReflectionException
     */
    protected function getActivityEntity(
        ?int $activityId = null,
        ?string $key = null
    ): Activity {
        $activityReflection = new ReflectionClass(Activity::class);

        /**
         * @var \EoneoPay\Webhooks\Bridge\Doctrine\Entities\Activity $activity
         */
        $activity = $activityReflection->newInstanceWithoutConstructor();
        $activity->setActivityKey($key ?? 'activity.key');
        $activity->setActivityId($activityId ?? 123);
        $activity->setOccurredAt(new DateTime('2100-01-01T10:11:12Z'));
        $activity->setPayload(['payload']);
        $activity->setPrimaryEntity(new EntityStub());

        return $activity;
    }

    /**
     * Returns a WebhookRequest entity.
     *
     * @param \EoneoPay\Webhooks\Model\ActivityInterface|null $activity
     * @param \DateTime|null $createdAt
     * @param int|null $requestId
     *
     * @return \EoneoPay\Webhooks\Bridge\Doctrine\Entities\WebhookRequest
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException
     * @throws \ReflectionException
     */
    protected function getRequestEntity(
        ?ActivityInterface $activity = null,
        ?BaseDateTime $createdAt = null,
        ?int $requestId = null
    ): WebhookRequest {
        $reflectionClass = new ReflectionClass(WebhookRequest::class);

        /**
         * @var \EoneoPay\Webhooks\Bridge\Doctrine\Entities\WebhookRequest $request
         */
        $request = $reflectionClass->newInstanceWithoutConstructor();
        $request->setActivity($activity ?? $this->getActivityEntity());
        $request->setRequestFormat('json');
        $request->setRequestHeaders(['header' => 'value']);
        $request->setRequestId($requestId ?? 123);
        $request->setRequestMethod('POST');
        $request->setRequestUrl('https://localhost.com/webhook');
        $request->setCreatedAt($createdAt ?? new DateTime('2099-10-10'));

        return $request;
    }

    /**
     * Returns a WebhookResponse entity.
     *
     * @param \EoneoPay\Webhooks\Bridge\Doctrine\Entities\WebhookRequest|null $request
     * @param int|null $responseId
     * @param int|null $statusCode
     *
     * @return \EoneoPay\Webhooks\Bridge\Doctrine\Entities\WebhookResponse
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException
     * @throws \ReflectionException
     */
    protected function getResponseEntity(
        ?WebhookRequest $request = null,
        ?int $responseId = null,
        ?int $statusCode = null
    ): WebhookResponse {
        $reflectionClass = new ReflectionClass(WebhookResponse::class);

        /**
         * @var \EoneoPay\Webhooks\Bridge\Doctrine\Entities\WebhookResponse $response
         */
        $response = $reflectionClass->newInstanceWithoutConstructor();
        $response->setRequest($request ?? $this->getRequestEntity());
        $response->setSuccessful(true);
        $response->setErrorReason('error_reason');
        $response->setResponse('RESPONSE');
        $response->setResponseId($responseId ?? 234);
        $response->setStatusCode($statusCode ?? 204);
        $response->setCreatedAt(new DateTime('2099-10-10'));

        return $response;
    }
}
