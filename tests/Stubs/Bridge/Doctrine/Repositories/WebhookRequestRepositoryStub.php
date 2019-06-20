<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Repositories;

use ArrayIterator;
use DateTime;
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
    public function getFailedRequestIds(DateTime $since): Iterator
    {
        $requestIds = [];
        $entities = $this->findAll();

        foreach($entities as $key => $entity){
            $requestIds[] = [$key => ['requestId' => $entity->getRequestId()]];
        }

        return new ArrayIterator($requestIds);
    }
}
