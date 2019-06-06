<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Bridge\Doctrine\Entities;

use EoneoPay\Utils\DateTime;
use EoneoPay\Webhooks\Bridge\Doctrine\Entities\Activity;
use EoneoPay\Webhooks\Bridge\Doctrine\Entities\WebhookRequest;
use EoneoPay\Webhooks\Bridge\Doctrine\Entities\WebhookResponse;
use ReflectionClass;
use Tests\EoneoPay\Webhooks\Stubs\Externals\EntityStub;
use Tests\EoneoPay\Webhooks\TestCase;

/**
 * @coversNothing
 */
abstract class BaseEntityTestCase extends TestCase
{
    /**
     * Returns the Activity entity.
     *
     * @return \EoneoPay\Webhooks\Bridge\Doctrine\Entities\Activity
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException
     * @throws \ReflectionException
     */
    protected function getActivityEntity(): Activity
    {
        $activityReflection = new ReflectionClass(Activity::class);

        /**
         * @var \EoneoPay\Webhooks\Bridge\Doctrine\Entities\Activity $activity
         */
        $activity = $activityReflection->newInstanceWithoutConstructor();
        $activity->setActivityKey('activity.key');
        $activity->setActivityId(123);
        $activity->setOccurredAt(new DateTime('2100-01-01T10:11:12Z'));
        $activity->setPayload(['payload']);
        $activity->setPrimaryEntity(new EntityStub());

        return $activity;
    }

    /**
     * Returns a WebhookRequest entity.
     *
     * @return \EoneoPay\Webhooks\Bridge\Doctrine\Entities\WebhookRequest
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException
     * @throws \ReflectionException
     */
    protected function getRequestEntity(): WebhookRequest
    {
        $reflectionClass = new ReflectionClass(WebhookRequest::class);

        /**
         * @var \EoneoPay\Webhooks\Bridge\Doctrine\Entities\WebhookRequest $request
         */
        $request = $reflectionClass->newInstanceWithoutConstructor();
        $request->setActivity($this->getActivityEntity());
        $request->setRequestFormat('json');
        $request->setRequestHeaders(['header' => 'value']);
        $request->setRequestId(123);
        $request->setRequestMethod('POST');
        $request->setRequestUrl('https://localhost.com/webhook');

        return $request;
    }

    /**
     * Returns a WebhookResponse entity.
     *
     * @return \EoneoPay\Webhooks\Bridge\Doctrine\Entities\WebhookResponse
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException
     * @throws \ReflectionException
     */
    protected function getResponseEntity(): WebhookResponse
    {
        $reflectionClass = new ReflectionClass(WebhookResponse::class);

        /**
         * @var \EoneoPay\Webhooks\Bridge\Doctrine\Entities\WebhookResponse $response
         */
        $response = $reflectionClass->newInstanceWithoutConstructor();
        $response->setRequest($this->getRequestEntity());
        $response->setSuccessful(true);
        $response->setErrorReason('error_reason');
        $response->setResponse('RESPONSE');
        $response->setResponseId(234);
        $response->setStatusCode(204);

        return $response;
    }
}
