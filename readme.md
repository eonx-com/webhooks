# EoneoPay Webhook Library

## Installation
Use [Composer](https://getcomposer.org/) to install the package in your project:

```
composer require eoneopay/webhooks
```

## Integration
#### Laravel
To integrate the package into your [Laravel](https://laravel.com) or [Lumen](https://lumen.laravel.com)
you need to register the following service providers:

```
\EoneoPay\Webhooks\Bridge\Laravel\Providers\WebhookServiceProvider
\EoneoPay\Webhooks\Bridge\Laravel\Providers\WebhookEventServiceProvider
```

Additionally:

- Implement and bind a service for the interface `EoneoPay\Webhooks\Subscription\Interfaces\SubscriptionRetrieverInterface`
- Any Entities that can subscribe to webhooks need to implement `EoneoPay\Webhooks\Subscription\Interfaces\SubscriberInterface`
- Your entities that represent a webhook subscription need to implement `EoneoPay\Webhooks\Subscription\Interfaces\SubscriptionInterface`
- You need to add a ResolveTargetEntityListener to the Doctrine ORM, this is 
  done by adding a new class, `ResolveTargetEntityExtension`, and a new config
  array key in config/doctrine.php
```php
<?php
declare(strict_types=1);

# Put this file where it makes sense for your application.

namespace App\External\Libraries\Doctrine\Extensions;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\EventManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\ResolveTargetEntityListener;
use LaravelDoctrine\ORM\Extensions\Extension;

class ResolveTargetEntityExtension implements Extension
{
    /**
     * @var \Doctrine\ORM\Tools\ResolveTargetEntityListener
     */
    private $rtel;

    /**
     * ResolveTargetEntityExtension constructor.
     *
     * @param \Doctrine\ORM\Tools\ResolveTargetEntityListener $rtel
     */
    public function __construct(ResolveTargetEntityListener $rtel)
    {
        $this->rtel = $rtel;
    }

    /**
     * @inheritdoc
     */
    public function addSubscribers(
        EventManager $eventManager,
        EntityManagerInterface $entityManager,
        ?Reader $reader = null
    ): void {
        $eventManager->addEventSubscriber($this->rtel);
    }

    /**
     * @inheritdoc
     */
    public function getFilters(): array
    {
        return [];
    }
}
```
- Add `\App\External\Libraries\Doctrine\Extensions\ResolveTargetEntityExtension::class` to `config/doctrine.php` under 'extensions'
- Add a new root array key under `config/doctrine.php` called `replacents`:
```php
<?php

return [
    // ...
    
    'replacements' => [
        \EoneoPay\Webhooks\Bridge\Doctrine\Entity\WebhookEntityInterface::class => \App\Entity\User\Webhook::class
    ] 
];
```
