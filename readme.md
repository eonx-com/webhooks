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

