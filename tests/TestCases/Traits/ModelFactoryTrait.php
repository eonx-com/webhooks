<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\TestCases\Traits;

use DateTime as BaseDateTime;
use EoneoPay\Utils\DateTime;
use EoneoPay\Webhooks\Bridge\Doctrine\Entities\Activity;
use EoneoPay\Webhooks\Bridge\Doctrine\Entities\Lifecycle\Request;
use EoneoPay\Webhooks\Bridge\Doctrine\Entities\Lifecycle\Response;
use EoneoPay\Webhooks\Models\ActivityInterface;
use EoneoPay\Webhooks\Models\WebhookRequestInterface;
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
     * @param \EoneoPay\Webhooks\Models\ActivityInterface|null $activity
     * @param \DateTime|null $createdAt
     * @param int|null $requestId
     *
     * @return \EoneoPay\Webhooks\Bridge\Doctrine\Entities\Lifecycle\Request
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException
     * @throws \ReflectionException
     */
    protected function getRequestEntity(
        ?ActivityInterface $activity = null,
        ?BaseDateTime $createdAt = null,
        ?int $requestId = null
    ): Request {
        $reflectionClass = new ReflectionClass(Request::class);

        /**
         * @var \EoneoPay\Webhooks\Bridge\Doctrine\Entities\Lifecycle\Request $request
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
     * @param \EoneoPay\Webhooks\Models\WebhookRequestInterface|null $request
     * @param string|null $responseId
     * @param int|null $statusCode
     *
     * @return \EoneoPay\Webhooks\Bridge\Doctrine\Entities\Lifecycle\Response
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException
     * @throws \ReflectionException
     */
    protected function getResponseEntity(
        ?WebhookRequestInterface $request = null,
        ?string $responseId = null,
        ?int $statusCode = null
    ): Response {
        $reflectionClass = new ReflectionClass(Response::class);

        /**
         * @var \EoneoPay\Webhooks\Bridge\Doctrine\Entities\Lifecycle\Response $response
         */
        $response = $reflectionClass->newInstanceWithoutConstructor();
        $response->setRequest($request ?? $this->getRequestEntity());
        $response->setSuccessful(true);
        $response->setErrorReason('error_reason');
        $response->setResponse('RESPONSE');
        $response->setResponseId($responseId ?? '234');
        $response->setStatusCode($statusCode ?? 204);
        $response->setCreatedAt(new DateTime('2099-10-10'));

        return $response;
    }
}
