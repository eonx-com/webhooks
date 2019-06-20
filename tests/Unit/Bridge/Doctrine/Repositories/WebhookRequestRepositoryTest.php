<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Bridge\Doctrine\Repositories;

use Tests\EoneoPay\Webhooks\DoctrineTestCase;

/**
 * @covers \EoneoPay\Webhooks\Bridge\Doctrine\Repositories\WebhookRequestRepository
 */
class WebhookRequestRepositoryTest extends DoctrineTestCase
{
    /**
     * Test doctrine initialises
     *
     * @return void
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function testInitialisingDoctrine(): void
    {
        $this->getEntityManager();
    }
}