<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entity;

use EoneoPay\Externals\HttpClient\Interfaces\ResponseInterface;
use EoneoPay\Webhooks\Bridge\Doctrine\Entity\WebhookRequestInterface;
use EoneoPay\Webhooks\Bridge\Doctrine\Entity\WebhookResponseInterface;
use Illuminate\Support\Collection;

class WebhookResponseStub implements WebhookResponseInterface
{
    /**
     * @var \Illuminate\Support\Collection
     */
    private $data;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->data = new Collection();
    }

    /**
     * Returns data
     *
     * @return \Illuminate\Support\Collection
     */
    public function getData(): Collection
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     */
    public function populate(WebhookRequestInterface $request, ResponseInterface $response): void
    {
        $this->data['request'] = $request;
        $this->data['response'] = $response;
    }
}
