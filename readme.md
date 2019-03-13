# EoneoPay Webhook Library

This library adds support for firing webhooks asyncronously to external subscribers.

## Installation
Use [Composer](https://getcomposer.org/) to install the package in your project:

```
composer require eoneopay/webhooks
```

## Usage

Inject the `\EoneoPay\Webhooks\Webhooks\Interface\WebhookInterface` service into your
application where a webhook needs to be fired. The send method on this interface accepts
a `WebhookDataInterface` implementation that represents a specific webhook to be fired.

For each of the different webhooks you will fire inside your application you will need
to create a class that implements `WebhookDataInterface`.

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
- Implement `EoneoPay\Webhooks\Bridge\Doctrine\Entity\WebhookEntityInterface` as
  a new entity, and use the 
  `EoneoPay\Webhooks\Bridge\Doctrine\Entity\Schemas\WebhookSchema` trait.
- Any Entities that can subscribe to webhooks need to implement `EoneoPay\Webhooks\Subscription\Interfaces\SubscriberInterface`
- Your entities that represent a webhook subscription need to implement `EoneoPay\Webhooks\Subscription\Interfaces\SubscriptionInterface`
- Add `EoneoPay\Externals\Bridge\Laravel\ORM\ResolveTargetEntityExtension` to
  `config/doctrine.php` under the `extensions` key
- Add a new root array key under `config/doctrine.php` called `replacents` with
  the following:
```php
<?php

return [
    // ...
    
    // The App\Entity\User\Webhook class should point to the implementation of your
    // WebhookEntityInterface.
    'replacements' => [
        \EoneoPay\Webhooks\Bridge\Doctrine\Entity\WebhookEntityInterface::class => \App\Entity\User\Webhook::class
    ] 
];
```
