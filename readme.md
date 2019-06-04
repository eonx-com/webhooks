# EoneoPay Activity/Webhook Library

This library adds support for creating Activities which are then fired as webhooks to 
subscribers of those activities.

## Installation
Use [Composer](https://getcomposer.org/) to install the package in your project:

```
composer require eoneopay/webhooks
```

## Usage

Inject the `\EoneoPay\Webhooks\Activities\Interface\ActivityFactoryInterface` service 
into your application where an activity needs to be created. The send method on this
interface accepts an `ActivityDataInterface` implementation that represents a specific
activity to be created.

For each of the different activities you will fire inside your application you will need
to create a class that implements `ActivityDataInterface`.

## Theory of Operation

- `ActivityFactoryInterface` receives an instance of `ActivityDataInterface`
  - The factory will then call the `PayloadManager` to build the payload for the `ActivityDataInterface`
  - The factory will take the payload and the `ActivityDataInterface` and save it as a new `ActivityInterface` entity.
  - Finally, the factory will dispatch an ActivityCreatedEvent
- The listeners will receive the event inside an asynchronous queue worker and call `WebhookManager#processActivity`
  - The WebhookManager will resolve any subscriptions for the activity
  - Then it will create a new WebhookRequest for each subscription
  - And dispatch a new WebhookRequestCreatedEvent.
- Another listener will accept this event and call `RequestProcessor#process`
  - Which builds a PSR7 Request
  - Sends the request
  - Records the result as a WebhookResponse  

## Integration
#### Laravel
To integrate the package into your [Laravel](https://laravel.com) or [Lumen](https://lumen.laravel.com)
you need to register the following service providers:

```
\EoneoPay\Webhooks\Bridge\Laravel\Providers\WebhookServiceProvider
\EoneoPay\Webhooks\Bridge\Laravel\Providers\WebhookEventServiceProvider
```

Any implementation of this library will need to:

- Implement and bind a service for the interface `EoneoPay\Webhooks\Subscription\Interfaces\SubscriptionResolverInterface`
- Add `EoneoPay\Externals\Bridge\Laravel\ORM\ResolveTargetEntityExtension` to
  `config/doctrine.php` under the `extensions` key
  
- Modify `config/doctrine.php` to add the entity path and namespace to the configuration:
```php
<?php
return [
    'managers' => [
        'default' => [
            // ...
            'namespaces' => [
                // ...
                'Eoneopay\\Webhooks\\Bridge\\Doctrine\\Entities'
            ],
            'paths' => [
                // ...
                \base_path('vendor/eoneopay/webhooks/src/Bridge/Doctrine/Entities')
            ]
            // ...
        ]
    ]
];
```

- Add a new root array key under `config/doctrine.php` called `replacents` with
  the following:
```php
<?php

use EoneoPay\Webhooks\Model\ActivityInterface;
use EoneoPay\Webhooks\Model\WebhookRequestInterface;
use EoneoPay\Webhooks\Model\WebhookResponseInterface;
use EoneoPay\Webhooks\Bridge\Doctrine\Entities\Activity;
use EoneoPay\Webhooks\Bridge\Doctrine\Entities\WebhookRequest;
use EoneoPay\Webhooks\Bridge\Doctrine\Entities\WebhookResponse;

return [
    // ...
    
    'replacements' => [
        ActivityInterface::class => Activity::class,
        WebhookRequestInterface::class => WebhookRequest::class,
        WebhookResponseInterface::class => WebhookResponse::class
    ] 
];
```
