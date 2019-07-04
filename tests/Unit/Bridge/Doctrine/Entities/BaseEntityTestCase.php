<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Bridge\Doctrine\Entities;

use Tests\EoneoPay\Webhooks\TestCase;
use Tests\EoneoPay\Webhooks\TestCases\Traits\ModelFactoryTrait;

/**
 * @coversNothing
 */
abstract class BaseEntityTestCase extends TestCase
{
    use ModelFactoryTrait;
}
