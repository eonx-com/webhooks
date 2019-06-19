<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Repositories;

use ArrayObject;
use DateTime;
use Doctrine\ORM\Internal\Hydration\IterableResult;
use EoneoPay\Webhooks\Bridge\Doctrine\Repositories\Interfaces\WebhookRequestRepositoryInterface;
use Iterator;
use Tests\EoneoPay\Webhooks\Stubs\Vendor\Doctrine\ORM\RepositoryStub;

/**
 * @coversNothing
 */
class WebhookRequestRepositoryStub extends RepositoryStub implements WebhookRequestRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getFailedRequests(DateTime $since): Iterator
    {
        $arrayObject = new ArrayObject($this->findAll());

        return $arrayObject->getIterator();
    }
}